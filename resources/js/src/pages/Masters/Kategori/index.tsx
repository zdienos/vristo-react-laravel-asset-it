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

interface Tipe { id_tipe: number; nama_tipe: string; }
interface Kategori { id_kategori: number; nama_kategori: string; id_tipe: number; tipe?: Tipe; }

const KategoriPage = () => {
    const dispatch = useDispatch();
    const [items, setItems] = useState<Kategori[]>([]);
    const [tipes, setTipes] = useState<Tipe[]>([]);
    const [modal, setModal] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [formData, setFormData] = useState<Partial<Kategori>>({});
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Kategori[]>([]);
    const [records, setRecords] = useState<Kategori[]>([]);
    const [sortStatus, setSortStatus] = useState<DataTableSortStatus>({ columnAccessor: 'nama_kategori', direction: 'asc' });
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Master Kategori')); fetchData(); }, []);

    useEffect(() => {
        const from = (page - 1) * pageSize;
        setRecords(initialRecords.slice(from, from + pageSize));
    }, [page, pageSize, initialRecords]);

    useEffect(() => {
        const data = sortBy(initialRecords, sortStatus.columnAccessor);
        setInitialRecords(sortStatus.direction === 'desc' ? data.reverse() : data);
    }, [sortStatus]);

    useEffect(() => {
        setInitialRecords(items.filter((item) => item.nama_kategori.toLowerCase().includes(search.toLowerCase())));
    }, [search, items]);

    const fetchData = async () => {
        try {
            const [katRes, tipeRes] = await Promise.all([axios.get('/api/kategoris'), axios.get('/api/tipes')]);
            setItems(katRes.data);
            setInitialRecords(katRes.data);
            setTipes(tipeRes.data);
        } catch (error) { console.error(error); }
    };

    const handleAdd = () => { setIsEdit(false); setFormData({}); setModal(true); };
    const handleEdit = (item: Kategori) => { setIsEdit(true); setFormData(item); setModal(true); };

    const handleDelete = (item: Kategori) => {
        Swal.fire({ title: 'Yakin hapus?', text: item.nama_kategori, icon: 'warning', showCancelButton: true, confirmButtonText: 'Hapus' }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.delete(`/api/kategoris/${item.id_kategori}`);
                Swal.fire('Terhapus!', '', 'success');
                fetchData();
            }
        });
    };

    const handleSave = async () => {
        if (!formData.nama_kategori || !formData.id_tipe) return;
        try {
            if (isEdit) {
                await axios.put(`/api/kategoris/${formData.id_kategori}`, formData);
            } else {
                await axios.post('/api/kategoris', formData);
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
                <h5 className="font-semibold text-lg dark:text-white-light">Master Kategori</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                    <button className="btn btn-primary" onClick={handleAdd}><PlusIcon className="w-4 h-4 mr-2" />Tambah</button>
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'nama_kategori', title: 'Nama Kategori', sortable: true },
                    { accessor: 'tipe.nama_tipe', title: 'Tipe', sortable: true },
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
                                        <h5 className="font-bold text-lg">{isEdit ? 'Edit' : 'Tambah'} Kategori</h5>
                                        <button onClick={() => setModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="mb-4">
                                            <label>Nama Kategori</label>
                                            <input className="form-input" value={formData.nama_kategori || ''} onChange={(e) => setFormData({ ...formData, nama_kategori: e.target.value })} />
                                        </div>
                                        <div className="mb-4">
                                            <label>Tipe</label>
                                            <select className="form-select" value={formData.id_tipe || ''} onChange={(e) => setFormData({ ...formData, id_tipe: Number(e.target.value) })}>
                                                <option value="">Pilih Tipe</option>
                                                {tipes.map((t) => <option key={t.id_tipe} value={t.id_tipe}>{t.nama_tipe}</option>)}
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

export default KategoriPage;
