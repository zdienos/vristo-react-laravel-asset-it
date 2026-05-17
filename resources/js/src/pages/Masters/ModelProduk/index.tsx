import { useEffect, useState, Fragment } from 'react';
import { DataTable, DataTableSortStatus } from 'mantine-datatable';
import { Dialog, Transition } from '@headlessui/react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import Swal from 'sweetalert2';
import { PencilIcon, Trash2Icon, PlusIcon, XIcon } from 'lucide-react';
import Tippy from '@tippyjs/react';
import sortBy from 'lodash/sortBy';

interface Kategori { id_kategori: number; nama_kategori: string; tipe?: { nama_tipe: string }; }
interface Manufaktur { id_manufaktur: number; nama_manufaktur: string; }
interface ModelProduk {
    id_model: number; nama_model: string;
    id_kategori: number; id_manufaktur: number;
    kategori?: Kategori; manufaktur?: Manufaktur;
}

const ModelProdukPage = () => {
    const dispatch = useDispatch();
    const [items, setItems] = useState<ModelProduk[]>([]);
    const [kategoris, setKategoris] = useState<Kategori[]>([]);
    const [manufakturs, setManufakturs] = useState<Manufaktur[]>([]);
    const [modal, setModal] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [formData, setFormData] = useState<Partial<ModelProduk>>({});
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<ModelProduk[]>([]);
    const [records, setRecords] = useState<ModelProduk[]>([]);
    const [sortStatus, setSortStatus] = useState<DataTableSortStatus>({ columnAccessor: 'nama_model', direction: 'asc' });
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Master Model Produk')); fetchData(); }, []);

    useEffect(() => {
        const from = (page - 1) * pageSize;
        setRecords(initialRecords.slice(from, from + pageSize));
    }, [page, pageSize, initialRecords]);

    useEffect(() => {
        const data = sortBy(initialRecords, sortStatus.columnAccessor);
        setInitialRecords(sortStatus.direction === 'desc' ? data.reverse() : data);
    }, [sortStatus]);

    useEffect(() => {
        setInitialRecords(items.filter((item) => item.nama_model.toLowerCase().includes(search.toLowerCase())));
    }, [search, items]);

    const fetchData = async () => {
        try {
            const [modelRes, katRes, manRes] = await Promise.all([
                axios.get('/api/model-produks'),
                axios.get('/api/kategoris'),
                axios.get('/api/manufakturs'),
            ]);
            setItems(modelRes.data);
            setInitialRecords(modelRes.data);
            setKategoris(katRes.data);
            setManufakturs(manRes.data);
        } catch (error) { console.error(error); }
    };

    const handleAdd = () => { setIsEdit(false); setFormData({}); setModal(true); };
    const handleEdit = (item: ModelProduk) => { setIsEdit(true); setFormData(item); setModal(true); };

    const handleDelete = (item: ModelProduk) => {
        Swal.fire({ title: 'Yakin hapus?', text: item.nama_model, icon: 'warning', showCancelButton: true, confirmButtonText: 'Hapus' }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.delete(`/api/model-produks/${item.id_model}`);
                Swal.fire('Terhapus!', '', 'success');
                fetchData();
            }
        });
    };

    const handleSave = async () => {
        if (!formData.nama_model || !formData.id_kategori || !formData.id_manufaktur) return;
        try {
            if (isEdit) {
                await axios.put(`/api/model-produks/${formData.id_model}`, formData);
            } else {
                await axios.post('/api/model-produks', formData);
            }
            Swal.fire('Berhasil!', '', 'success');
            fetchData();
            setModal(false);
        } catch (error: any) {
            Swal.fire('Error!', error.response?.data?.message || 'Gagal menyimpan', 'error');
        }
    };

    return (
        <div className="panel">
            <div className="flex md:items-center md:flex-row flex-col mb-5 gap-5">
                <h5 className="font-semibold text-lg dark:text-white-light">Master Model Produk</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                    <button className="btn btn-primary" onClick={handleAdd}><PlusIcon className="w-4 h-4 mr-2" />Tambah</button>
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'nama_model', title: 'Nama Model', sortable: true },
                    { accessor: 'kategori.nama_kategori', title: 'Kategori' },
                    { accessor: 'kategori.tipe.nama_tipe', title: 'Tipe' },
                    { accessor: 'manufaktur.nama_manufaktur', title: 'Manufaktur' },
                    {
                        accessor: 'actions', title: 'Aksi', textAlignment: 'center',
                        render: (item) => (
                            <div className="flex gap-4 items-center w-max mx-auto">
                                <Tippy content="Edit"><button onClick={() => handleEdit(item)}><PencilIcon className="w-4 h-4 text-info" /></button></Tippy>
                                <Tippy content="Hapus"><button onClick={() => handleDelete(item)}><Trash2Icon className="w-4 h-4 text-danger" /></button></Tippy>
                            </div>
                        ),
                    },
                ]}
                totalRecords={initialRecords.length}
                recordsPerPage={pageSize}
                page={page}
                onPageChange={setPage}
                recordsPerPageOptions={PAGE_SIZES}
                onRecordsPerPageChange={setPageSize}
                sortStatus={sortStatus}
                onSortStatusChange={setSortStatus}
                minHeight={200}
            />

            <Transition appear show={modal} as={Fragment}>
                <Dialog as="div" open={modal} onClose={() => setModal(false)} className="relative z-50">
                    <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0" enterTo="opacity-100" leave="ease-in duration-200" leaveFrom="opacity-100" leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-[black]/60" />
                    </Transition.Child>
                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center px-4 py-8">
                            <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100" leave="ease-in duration-200" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95">
                                <Dialog.Panel className="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg">
                                    <div className="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                                        <h5 className="font-bold text-lg">{isEdit ? 'Edit' : 'Tambah'} Model</h5>
                                        <button onClick={() => setModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="mb-4">
                                            <label>Nama Model</label>
                                            <input className="form-input" value={formData.nama_model || ''} onChange={(e) => setFormData({ ...formData, nama_model: e.target.value })} />
                                        </div>
                                        <div className="mb-4">
                                            <label>Kategori</label>
                                            <select className="form-select" value={formData.id_kategori || ''} onChange={(e) => setFormData({ ...formData, id_kategori: Number(e.target.value) })}>
                                                <option value="">Pilih Kategori</option>
                                                {kategoris.map((k) => <option key={k.id_kategori} value={k.id_kategori}>{k.nama_kategori} ({k.tipe?.nama_tipe})</option>)}
                                            </select>
                                        </div>
                                        <div className="mb-4">
                                            <label>Manufaktur</label>
                                            <select className="form-select" value={formData.id_manufaktur || ''} onChange={(e) => setFormData({ ...formData, id_manufaktur: Number(e.target.value) })}>
                                                <option value="">Pilih Manufaktur</option>
                                                {manufakturs.map((m) => <option key={m.id_manufaktur} value={m.id_manufaktur}>{m.nama_manufaktur}</option>)}
                                            </select>
                                        </div>
                                        <div className="flex justify-end gap-2 mt-6">
                                            <button className="btn btn-outline-danger" onClick={() => setModal(false)}>Batal</button>
                                            <button className="btn btn-primary" onClick={handleSave}>Simpan</button>
                                        </div>
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </div>
    );
};

export default ModelProdukPage;
