import { useEffect, useState, Fragment } from 'react';
import { DataTable, DataTableSortStatus } from 'mantine-datatable';
import { Dialog, Transition } from '@headlessui/react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import Swal from 'sweetalert2';
import { PencilIcon, Trash2Icon, PlusIcon, XIcon, ArrowUpIcon, ArrowDownIcon } from 'lucide-react';
import Tippy from '@tippyjs/react';
import sortBy from 'lodash/sortBy';

interface Asset {
    id_asset: number; asset_tag: string; nama_asset: string; no_seri: string;
    id_model: number; id_status: number; id_pengguna: number | null; id_lokasi: number | null;
    assign_to: number | null; assign_type: string | null; keterangan: string;
    model?: any; status?: any; pengguna?: any; lokasi?: any;
}

const AssetPage = () => {
    const dispatch = useDispatch();
    const [items, setItems] = useState<Asset[]>([]);
    const [modal, setModal] = useState(false);
    const [checkoutModal, setCheckoutModal] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [formData, setFormData] = useState<Partial<Asset>>({});
    const [checkoutData, setCheckoutData] = useState<any>({});
    const [models, setModels] = useState<any[]>([]);
    const [statuses, setStatuses] = useState<any[]>([]);
    const [penggunas, setPenggunas] = useState<any[]>([]);
    const [lokasis, setLokasis] = useState<any[]>([]);
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Asset[]>([]);
    const [records, setRecords] = useState<Asset[]>([]);
    const [sortStatus, setSortStatus] = useState<DataTableSortStatus>({ columnAccessor: 'asset_tag', direction: 'asc' });
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Manajemen Aset')); fetchData(); }, []);

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
            item.nama_asset.toLowerCase().includes(search.toLowerCase()) ||
            item.asset_tag.toLowerCase().includes(search.toLowerCase())
        ));
    }, [search, items]);

    const fetchData = async () => {
        try {
            const [assetRes, modelRes, statusRes, penggunaRes, lokasiRes] = await Promise.all([
                axios.get('/api/assets'),
                axios.get('/api/model-produks'),
                axios.get('/api/tipes'),
                axios.get('/api/penggunas'),
                axios.get('/api/lokasis'),
            ]);
            setItems(assetRes.data);
            setInitialRecords(assetRes.data);
            setModels(modelRes.data);
            setStatuses([
                { id_status: 1, nama_status: 'Dipakai' },
                { id_status: 2, nama_status: 'Ready' },
                { id_status: 3, nama_status: 'Diperbaiki' },
                { id_status: 4, nama_status: 'Rusak' },
                { id_status: 5, nama_status: 'Dijual' },
            ]);
            setPenggunas(penggunaRes.data);
            setLokasis(lokasiRes.data);
        } catch (error) { console.error(error); }
    };

    const generateTag = async () => {
        try {
            const res = await axios.get('/api/assets/generate-tag');
            setFormData({ ...formData, asset_tag: res.data.asset_tag });
        } catch (error) { console.error(error); }
    };

    const handleAdd = () => { setIsEdit(false); setFormData({}); generateTag(); setModal(true); };
    const handleEdit = (item: Asset) => { setIsEdit(true); setFormData(item); setModal(true); };

    const handleDelete = (item: Asset) => {
        Swal.fire({ title: 'Yakin hapus?', text: item.nama_asset, icon: 'warning', showCancelButton: true, confirmButtonText: 'Hapus' }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.delete(`/api/assets/${item.id_asset}`);
                Swal.fire('Terhapus!', '', 'success');
                fetchData();
            }
        });
    };

    const handleSave = async () => {
        if (!formData.asset_tag || !formData.nama_asset || !formData.id_model || !formData.id_status) return;
        try {
            if (isEdit) {
                await axios.put(`/api/assets/${formData.id_asset}`, formData);
            } else {
                await axios.post('/api/assets', formData);
            }
            Swal.fire('Berhasil!', '', 'success');
            fetchData();
            setModal(false);
        } catch (error: any) {
            Swal.fire('Error!', error.response?.data?.message || 'Gagal menyimpan', 'error');
        }
    };

    const handleCheckout = (item: Asset) => {
        setCheckoutData({ id_asset: item.id_asset, id_status: 1, assign_type: 'pengguna' });
        setCheckoutModal(true);
    };

    const handleCheckin = (item: Asset) => {
        Swal.fire({ title: 'Checkin Aset?', text: item.nama_asset, icon: 'question', showCancelButton: true, confirmButtonText: 'Checkin' }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.post('/api/assets/checkin', { id_asset: item.id_asset, id_status: 2 });
                Swal.fire('Berhasil!', 'Aset sudah dikembalikan', 'success');
                fetchData();
            }
        });
    };

    const doCheckout = async () => {
        try {
            await axios.post('/api/assets/checkout', checkoutData);
            Swal.fire('Berhasil!', 'Checkout berhasil', 'success');
            fetchData();
            setCheckoutModal(false);
        } catch (error: any) {
            Swal.fire('Error!', error.response?.data?.message || 'Gagal checkout', 'error');
        }
    };

    return (
        <div className="panel">
            <div className="flex md:items-center md:flex-row flex-col mb-5 gap-5">
                <h5 className="font-semibold text-lg dark:text-white-light">Manajemen Aset</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                    <button className="btn btn-primary" onClick={handleAdd}><PlusIcon className="w-4 h-4 mr-2" />Tambah</button>
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'asset_tag', title: 'Tag', sortable: true },
                    { accessor: 'nama_asset', title: 'Nama Aset', sortable: true },
                    { accessor: 'model.nama_model', title: 'Model' },
                    { accessor: 'status.nama_status', title: 'Status', render: (item) => <span className={`badge ${item.status?.id_status === 1 ? 'bg-success' : item.status?.id_status === 2 ? 'bg-info' : 'bg-warning'}`}>{item.status?.nama_status}</span> },
                    { accessor: 'pengguna.nama_pengguna', title: 'Pengguna' },
                    {
                        accessor: 'actions', title: 'Aksi', textAlignment: 'center',
                        render: (item) => (
                            <div className="flex gap-2 items-center w-max mx-auto">
                                <Tippy content="Edit"><button onClick={() => handleEdit(item)}><PencilIcon className="w-4 h-4 text-info" /></button></Tippy>
                                {item.id_status === 2 && <Tippy content="Checkout"><button onClick={() => handleCheckout(item)}><ArrowUpIcon className="w-4 h-4 text-success" /></button></Tippy>}
                                {item.id_status === 1 && <Tippy content="Checkin"><button onClick={() => handleCheckin(item)}><ArrowDownIcon className="w-4 h-4 text-warning" /></button></Tippy>}
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

            {/* Modal Tambah/Edit */}
            <Transition appear show={modal} as={Fragment}>
                <Dialog as="div" open={modal} onClose={() => setModal(false)} className="relative z-50">
                    <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0" enterTo="opacity-100" leave="ease-in duration-200" leaveFrom="opacity-100" leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-[black]/60" />
                    </Transition.Child>
                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center px-4 py-8">
                            <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100" leave="ease-in duration-200" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95">
                                <Dialog.Panel className="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-2xl">
                                    <div className="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                                        <h5 className="font-bold text-lg">{isEdit ? 'Edit' : 'Tambah'} Aset</h5>
                                        <button onClick={() => setModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="grid grid-cols-2 gap-4">
                                            <div className="mb-4">
                                                <label>Asset Tag</label>
                                                <input className="form-input" value={formData.asset_tag || ''} onChange={(e) => setFormData({ ...formData, asset_tag: e.target.value })} />
                                            </div>
                                            <div className="mb-4">
                                                <label>Nama Aset</label>
                                                <input className="form-input" value={formData.nama_asset || ''} onChange={(e) => setFormData({ ...formData, nama_asset: e.target.value })} />
                                            </div>
                                            <div className="mb-4">
                                                <label>No Seri</label>
                                                <input className="form-input" value={formData.no_seri || ''} onChange={(e) => setFormData({ ...formData, no_seri: e.target.value })} />
                                            </div>
                                            <div className="mb-4">
                                                <label>Model</label>
                                                <select className="form-select" value={formData.id_model || ''} onChange={(e) => setFormData({ ...formData, id_model: Number(e.target.value) })}>
                                                    <option value="">Pilih Model</option>
                                                    {models.map((m: any) => <option key={m.id_model} value={m.id_model}>{m.nama_model}</option>)}
                                                </select>
                                            </div>
                                            <div className="mb-4">
                                                <label>Status</label>
                                                <select className="form-select" value={formData.id_status || ''} onChange={(e) => setFormData({ ...formData, id_status: Number(e.target.value) })}>
                                                    <option value="">Pilih Status</option>
                                                    {statuses.map((s: any) => <option key={s.id_status} value={s.id_status}>{s.nama_status}</option>)}
                                                </select>
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

            {/* Modal Checkout */}
            <Transition appear show={checkoutModal} as={Fragment}>
                <Dialog as="div" open={checkoutModal} onClose={() => setCheckoutModal(false)} className="relative z-50">
                    <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0" enterTo="opacity-100" leave="ease-in duration-200" leaveFrom="opacity-100" leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-[black]/60" />
                    </Transition.Child>
                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center px-4 py-8">
                            <Transition.Child as={Fragment} enter="ease-out duration-300" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100" leave="ease-in duration-200" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95">
                                <Dialog.Panel className="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg">
                                    <div className="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                                        <h5 className="font-bold text-lg">Checkout Aset</h5>
                                        <button onClick={() => setCheckoutModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="mb-4">
                                            <label>Assign Ke</label>
                                            <select className="form-select" value={checkoutData.assign_type || ''} onChange={(e) => setCheckoutData({ ...checkoutData, assign_type: e.target.value, assign_to: null })}>
                                                <option value="pengguna">Pengguna</option>
                                                <option value="lokasi">Lokasi</option>
                                            </select>
                                        </div>
                                        <div className="mb-4">
                                            <label>{checkoutData.assign_type === 'lokasi' ? 'Lokasi' : 'Pengguna'}</label>
                                            <select className="form-select" value={checkoutData.assign_to || ''} onChange={(e) => setCheckoutData({ ...checkoutData, assign_to: Number(e.target.value) })}>
                                                <option value="">Pilih</option>
                                                {(checkoutData.assign_type === 'lokasi' ? lokasis : penggunas).map((item: any) => (
                                                    <option key={item.id_lokasi || item.id_pengguna} value={item.id_lokasi || item.id_pengguna}>
                                                        {item.nama_lokasi || item.nama_pengguna}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="flex justify-end gap-2 mt-6">
                                            <button className="btn btn-outline-danger" onClick={() => setCheckoutModal(false)}>Batal</button>
                                            <button className="btn btn-primary" onClick={doCheckout}>Checkout</button>
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

export default AssetPage;
