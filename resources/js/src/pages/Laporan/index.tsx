import { useEffect, useState } from 'react';
import { DataTable } from 'mantine-datatable';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';

interface Log {
    id_log: number; log_user: string; log_tipe: string; log_aksi: string;
    log_item: number; log_assign_to: number | null; log_assign_type: string | null;
    created_at: string;
}

const LaporanPage = () => {
    const dispatch = useDispatch();
    const [logs, setLogs] = useState<Log[]>([]);
    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Log[]>([]);
    const [records, setRecords] = useState<Log[]>([]);
    const [search, setSearch] = useState('');

    useEffect(() => { dispatch(setPageTitle('Laporan Aktivitas')); fetchData(); }, []);

    useEffect(() => {
        const from = (page - 1) * pageSize;
        setRecords(initialRecords.slice(from, from + pageSize));
    }, [page, pageSize, initialRecords]);

    useEffect(() => {
        setInitialRecords(logs.filter((log) =>
            log.log_user.toLowerCase().includes(search.toLowerCase()) ||
            log.log_tipe.toLowerCase().includes(search.toLowerCase()) ||
            log.log_aksi.toLowerCase().includes(search.toLowerCase())
        ));
    }, [search, logs]);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/laporan/aktivitas');
            setLogs(res.data.logs.data || res.data.logs);
            setInitialRecords(res.data.logs.data || res.data.logs);
        } catch (error) { console.error(error); }
    };

    const getTipeBadge = (tipe: string) => {
        const colors: any = { asset: 'bg-primary', asesoris: 'bg-success', inventori: 'bg-warning', komponen: 'bg-info' };
        return <span className={`badge ${colors[tipe] || 'bg-secondary'}`}>{tipe}</span>;
    };

    return (
        <div className="panel">
            <div className="flex md:items-center md:flex-row flex-col mb-5 gap-5">
                <h5 className="font-semibold text-lg dark:text-white-light">Laporan Aktivitas</h5>
                <div className="flex gap-2 ltr:ml-auto rtl:mr-auto">
                    <input type="text" placeholder="Cari..." className="form-input w-60" value={search} onChange={(e) => setSearch(e.target.value)} />
                </div>
            </div>
            <DataTable
                highlightOnHover
                records={records}
                columns={[
                    { accessor: 'log_user', title: 'User', sortable: true },
                    { accessor: 'log_tipe', title: 'Tipe', render: (item) => getTipeBadge(item.log_tipe) },
                    { accessor: 'log_aksi', title: 'Aksi', sortable: true },
                    { accessor: 'log_assign_type', title: 'Target' },
                    {
                        accessor: 'created_at', title: 'Waktu', sortable: true,
                        render: (item) => new Date(item.created_at).toLocaleString('id-ID'),
                    },
                ]}
                totalRecords={initialRecords.length}
                recordsPerPage={pageSize}
                page={page}
                onPageChange={setPage}
                recordsPerPageOptions={PAGE_SIZES}
                onRecordsPerPageChange={setPageSize}
                minHeight={200}
            />
        </div>
    );
};

export default LaporanPage;
