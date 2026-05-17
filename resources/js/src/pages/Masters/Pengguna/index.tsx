import { useEffect, useState, Fragment } from 'react';
import { DataTable, DataTableSortStatus } from 'mantine-datatable';
import { Dialog, Transition } from '@headlessui/react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import Swal from 'sweetalert2';
import { PencilIcon, Trash2Icon, PlusIcon, XIcon, EyeIcon } from 'lucide-react';
import Tippy from '@tippyjs/react';
import sortBy from 'lodash/sortBy';

interface Departemen { id_departemen: number; nama_departemen: string; }
interface Lokasi { id_lokasi: number; nama_lokasi: string; }
interface Pengguna {
    id_pengguna: number; nik: string; nama_pengguna: string;
    id_departemen: number; id_lokasi: number;
    telepon: string; alamat: string; keterangan: string;
    departemen?: Departemen; lokasi?: Lokasi;
}

const PenggunaPage = () => {
    const dispatch = useDispatch();
    const [items, setItems] = useState<Pengguna[]>([]);
    const [departemens, setDepartemens] = useState<Departemen[]>([]);
    const [lokasis, setLokasis] = useState<Lokasi[]>([]);
    const [modal, setModal] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [formData, setFormData] = useState<Partial<Pengguna>>({});
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Pengguna[]>([]);
    const [records, setRecords] = useState<Pengguna[]>([]);
    const [sortStatus, setSortStatus] = useState<DataTableSortStatus>({ columnAccessor: 'nama_pengguna', direction: 'asc' });
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Master Pengguna')); fetchData(); }, []);

    useEffect(() => {
        const from = (page - 1) * pageSize;
        setRecords(initialRecords.slice(from, from + pageSize));
    }, [page, pageSize, initialRecords]);

    useEffect(() => {
        const data = sortBy(initialRecords, sortStatus.columnAccessor);
        setInitialRecords(sortStatus.direction === 'desc' ? data.reverse() : data);
    }, [sortStatus]);

    useEffect(() => {
        setInitialRecords(items.filter((item) =>
            item.nama_pengguna.toLowerCase().includes(search.toLowerCase()) ||
            item.nik.toLowerCase().includes(search.toLowerCase())
        ));
    }, [search, items]);

    const fetchData = async () => {
        try {
            const [penggunaRes, deptRes, lokRes] = await Promise.all([
                axios.get('/api/penggunas'),
                axios.get('/api/departemens'),
                axios.get('/api/lokasis'),
            ]);
            setItems(penggunaRes.data);
            setInitialRecords(penggunaRes.data);
            setDepartemens(deptRes.data);
            setLokasis(lokRes.data);
        } catch (error) { console.error(error); }
    };

    const handleAdd = () => { setIsEdit(false); setFormData({}); setModal(true); };
    const handleEdit = (item: Pengguna) => { setIsEdit(true); setFormData(item); setModal(true); };

    const handleDelete = (item: Pengguna) => {
        Swal.fire({ title: 'Yakin hapus?', text: item.nama_pengguna, icon: 'warning', showCancelButton: true, confirmButtonText: 'Hapus' }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.delete(`/api/penggunas/${item.id_pengguna}`);
                Swal.fire('Terhapus!', '', 'success');
                fetchData();
            }
        });
    };

    const handleSave = async () => {
        if (!formData.nik || !formData.nama_pengguna || !formData.id_departemen || !formData.id_lokasi) return;
        try {
            if (isEdit) {
                await axios.put(`/api/penggunas/${formData.id_pengguna}`, formData);
            } else {
                await axios.post('/api/penggunas', formData);
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
                <h5 className="font-semibold text-lg dark:text-white-light">Master Pengguna</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                    <button className="btn btn-primary" onClick={handleAdd}><PlusIcon className="w-4 h-4 mr-2" />Tambah</button>
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'nik', title: 'NIK', sortable: true },
                    { accessor: 'nama_pengguna', title: 'Nama', sortable: true },
                    { accessor: 'departemen.nama_departemen', title: 'Departemen' },
                    { accessor: 'lokasi.nama_lokasi', title: 'Lokasi' },
                    { accessor: 'telepon', title: 'Telepon' },
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
                                        <h5 className="font-bold text-lg">{isEdit ? 'Edit' : 'Tambah'} Pengguna</h5>
                                        <button onClick={() => setModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="grid grid-cols-2 gap-4">
                                            <div className="mb-4">
                                                <label>NIK</label>
                                                <input className="form-input" value={formData.nik || ''} onChange={(e) => setFormData({ ...formData, nik: e.target.value })} />
                                            </div>
                                            <div className="mb-4">
                                                <label>Nama</label>
                                                <input className="form-input" value={formData.nama_pengguna || ''} onChange={(e) => setFormData({ ...formData, nama_pengguna: e.target.value })} />
                                            </div>
                                            <div className="mb-4">
                                                <label>Departemen</label>
                                                <select className="form-select" value={formData.id_departemen || ''} onChange={(e) => setFormData({ ...formData, id_departemen: Number(e.target.value) })}>
                                                    <option value="">Pilih</option>
                                                    {departemens.map((d) => <option key={d.id_departemen} value={d.id_departemen}>{d.nama_departemen}</option>)}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label>Lokasi</label>
                                                <select className="form-select" value={formData.id_lokasi || ''} onChange={(e) => setFormData({ ...formData, id_lokasi: Number(e.target.value) })}>
                                                    <option value="">Pilih</option>
                                                    {lokasis.map((l) => <option key={l.id_lokasi} value={l.id_lokasi}>{l.nama_lokasi}</option>)}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label>Telepon</label>
                                                <input className="form-input" value={formData.telepon || ''} onChange={(e) => setFormData({ ...formData, telepon: e.target.value })} />
                                            </div>
                                            <div className="mb-4 col-span-2">
                                                <label>Alamat</label>
                                                <textarea className="form-textarea" value={formData.alamat || ''} onChange={(e) => setFormData({ ...formData, alamat: e.target.value })} />
                                            </div>
                                            <div className="mb-4 col-span-2">
                                                <label>Keterangan</label>
                                                <textarea className="form-textarea" value={formData.keterangan || ''} onChange={(e) => setFormData({ ...formData, keterangan: e.target.value })} />
                                            </div>
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

export default PenggunaPage;
