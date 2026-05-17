import { useEffect, useState, Fragment } from 'react';
import { DataTable, DataTableSortStatus } from 'mantine-datatable';
import { Dialog, Transition } from '@headlessui/react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import Swal from 'sweetalert2';
import { XIcon } from 'lucide-react';
import sortBy from 'lodash/sortBy';

interface Komponen {
    id_komponen: number; id_model: number; stok: number; digunakan: number; rusak: number;
    model?: any;
}

const KomponenPage = () => {
    const dispatch = useDispatch();
    const [items, setItems] = useState<Komponen[]>([]);
    const [modal, setModal] = useState(false);
    const [modalType, setModalType] = useState<'masuk' | 'keluar' | 'checkin'>('masuk');
    const [formData, setFormData] = useState<any>({});
    const [assets, setAssets] = useState<any[]>([]);
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Komponen[]>([]);
    const [records, setRecords] = useState<Komponen[]>([]);
    const [sortStatus, setSortStatus] = useState<DataTableSortStatus>({ columnAccessor: 'model.nama_model', direction: 'asc' });
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Komponen')); fetchData(); }, []);

    useEffect(() => {
        const from = (page - 1) * pageSize;
        setRecords(initialRecords.slice(from, from + pageSize));
    }, [page, pageSize, initialRecords]);

    useEffect(() => {
        const data = sortBy(initialRecords, sortStatus.columnAccessor);
        setInitialRecords(sortStatus.direction === 'desc' ? data.reverse() : data);
    }, [sortStatus]);

    useEffect(() => {
        setInitialRecords(items.filter((item) => item.model?.nama_model?.toLowerCase().includes(search.toLowerCase())));
    }, [search, items]);

    const fetchData = async () => {
        try {
            const [komRes, assetRes] = await Promise.all([
                axios.get('/api/komponen'),
                axios.get('/api/assets'),
            ]);
            setItems(komRes.data);
            setInitialRecords(komRes.data);
            setAssets(assetRes.data);
        } catch (error) { console.error(error); }
    };

    const openMasuk = (item: Komponen) => {
        setModalType('masuk');
        setFormData({ id_komponen: item.id_komponen, jumlah: 1 });
        setModal(true);
    };

    const openKeluar = (item: Komponen) => {
        setModalType('keluar');
        setFormData({ id_komponen: item.id_komponen, jumlah: 1, id_asset: '' });
        setModal(true);
    };

    const openCheckin = (item: Komponen) => {
        setModalType('checkin');
        setFormData({ id_komponen: item.id_komponen, jumlah: 1 });
        setModal(true);
    };

    const handleSave = async () => {
        try {
            if (modalType === 'masuk') {
                await axios.post('/api/komponen/stok-masuk', formData);
            } else if (modalType === 'keluar') {
                if (!formData.id_asset) return;
                await axios.post('/api/komponen/checkout', formData);
            } else {
                await axios.post('/api/komponen/checkin', formData);
            }
            Swal.fire('Berhasil!', '', 'success');
            fetchData();
            setModal(false);
        } catch (error: any) {
            Swal.fire('Error!', error.response?.data?.message || 'Gagal', 'error');
        }
    };

    return (
        <div className="panel">
            <div className="flex md:items-center md:flex-row flex-col mb-5 gap-5">
                <h5 className="font-semibold text-lg dark:text-white-light">Komponen</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'model.nama_model', title: 'Nama Komponen', sortable: true },
                    { accessor: 'stok', title: 'Stok', sortable: true },
                    { accessor: 'digunakan', title: 'Digunakan', sortable: true },
                    { accessor: 'rusak', title: 'Rusak', sortable: true },
                    {
                        accessor: 'actions', title: 'Aksi', textAlignment: 'center',
                        render: (item) => (
                            <div className="flex gap-2 items-center w-max mx-auto">
                                <button className="btn btn-sm btn-success" onClick={() => openMasuk(item)}>+ Stok</button>
                                <button className="btn btn-sm btn-warning" onClick={() => openKeluar(item)}>Pasang</button>
                                <button className="btn btn-sm btn-info" onClick={() => openCheckin(item)}>Lepas</button>
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
                                <Dialog.Panel className="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-md">
                                    <div className="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                                        <h5 className="font-bold text-lg">{modalType === 'masuk' ? 'Stok Masuk' : modalType === 'keluar' ? 'Pasang ke Aset' : 'Lepas dari Aset'}</h5>
                                        <button onClick={() => setModal(false)}><XIcon /></button>
                                    </div>
                                    <div className="p-5">
                                        <div className="mb-4">
                                            <label>Jumlah</label>
                                            <input type="number" className="form-input" value={formData.jumlah || 1} onChange={(e) => setFormData({ ...formData, jumlah: Number(e.target.value) })} />
                                        </div>
                                        {modalType === 'keluar' && (
                                            <div className="mb-4">
                                                <label>Aset</label>
                                                <select className="form-select" value={formData.id_asset || ''} onChange={(e) => setFormData({ ...formData, id_asset: Number(e.target.value) })}>
                                                    <option value="">Pilih Aset</option>
                                                    {assets.map((a: any) => <option key={a.id_asset} value={a.id_asset}>{a.asset_tag} - {a.nama_asset}</option>)}
                                                </select>
                                            </div>
                                        )}
                                        <div className="mb-4">
                                            <label>Keterangan</label>
                                            <input className="form-input" value={formData.keterangan || ''} onChange={(e) => setFormData({ ...formData, keterangan: e.target.value })} />
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

export default KomponenPage;
