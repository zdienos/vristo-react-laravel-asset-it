import { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import { Box, Layers, Package, Cpu, Activity } from 'lucide-react';

interface DashboardData {
    asset: { total: number; in_use: number; ready: number; repair: number; damaged: number; sold: number };
    asesoris: { stok: number; digunakan: number };
    inventori: { stok: number };
    komponen: { stok: number; digunakan: number };
    recent_logs: any[];
}

const Dashboard = () => {
    const dispatch = useDispatch();
    const [data, setData] = useState<DashboardData | null>(null);

    useEffect(() => {
        dispatch(setPageTitle('Dashboard'));
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/dashboard');
            setData(res.data);
        } catch (error) {
            console.error('Failed to fetch dashboard', error);
        }
    };

    if (!data) return <div>Loading...</div>;

    return (
        <div>
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
                <div className="panel">
                    <div className="flex items-center justify-between">
                        <div>
                            <h5 className="text-lg font-semibold dark:text-white-light">Total Aset</h5>
                            <p className="text-2xl font-bold mt-2">{data.asset.total}</p>
                        </div>
                        <div className="bg-primary/10 text-primary rounded-xl p-3">
                            <Box className="w-6 h-6" />
                        </div>
                    </div>
                    <div className="flex gap-4 mt-3 text-sm">
                        <span className="text-success">Dipakai: {data.asset.in_use}</span>
                        <span className="text-info">Ready: {data.asset.ready}</span>
                        <span className="text-warning">Repair: {data.asset.repair}</span>
                    </div>
                </div>

                <div className="panel">
                    <div className="flex items-center justify-between">
                        <div>
                            <h5 className="text-lg font-semibold dark:text-white-light">Asesoris</h5>
                            <p className="text-2xl font-bold mt-2">{data.asesoris.stok}</p>
                        </div>
                        <div className="bg-success/10 text-success rounded-xl p-3">
                            <Layers className="w-6 h-6" />
                        </div>
                    </div>
                    <p className="text-sm mt-3 text-gray-500">Digunakan: {data.asesoris.digunakan}</p>
                </div>

                <div className="panel">
                    <div className="flex items-center justify-between">
                        <div>
                            <h5 className="text-lg font-semibold dark:text-white-light">Inventori</h5>
                            <p className="text-2xl font-bold mt-2">{data.inventori.stok}</p>
                        </div>
                        <div className="bg-warning/10 text-warning rounded-xl p-3">
                            <Package className="w-6 h-6" />
                        </div>
                    </div>
                </div>

                <div className="panel">
                    <div className="flex items-center justify-between">
                        <div>
                            <h5 className="text-lg font-semibold dark:text-white-light">Komponen</h5>
                            <p className="text-2xl font-bold mt-2">{data.komponen.stok}</p>
                        </div>
                        <div className="bg-danger/10 text-danger rounded-xl p-3">
                            <Cpu className="w-6 h-6" />
                        </div>
                    </div>
                    <p className="text-sm mt-3 text-gray-500">Digunakan: {data.komponen.digunakan}</p>
                </div>
            </div>

            <div className="panel">
                <h5 className="text-lg font-semibold dark:text-white-light mb-5">Aktivitas Terakhir</h5>
                <div className="table-responsive">
                    <table className="table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.recent_logs.map((log, i) => (
                                <tr key={i}>
                                    <td>{log.log_user}</td>
                                    <td><span className="badge bg-primary">{log.log_tipe}</span></td>
                                    <td>{log.log_aksi}</td>
                                    <td>{new Date(log.created_at).toLocaleString('id-ID')}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
