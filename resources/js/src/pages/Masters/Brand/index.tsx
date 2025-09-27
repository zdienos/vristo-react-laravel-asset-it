import { useEffect, useState, Fragment } from 'react';
import sortBy from 'lodash/sortBy';
import { Dialog, Transition } from '@headlessui/react';
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '@/store/themeConfigSlice';
import axios from '@/lib/axios';
import Swal from 'sweetalert2';
import { Trash, Pencil, X, Plus, PencilIcon, XIcon, Trash2Icon, PlusIcon } from 'lucide-react';
import Table from './Table';

// Definisikan tipe data untuk Brand
interface Brand {
    id: number;
    name: string;
}

const Main = () => {
    const dispatch = useDispatch();
    useEffect(() => {
        dispatch(setPageTitle('Master Brand'));
    });

    const [items, setItems] = useState<Brand[]>([]);
    const [modal, setModal] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [formData, setFormData] = useState<Partial<Brand>>({});

    const [page, setPage] = useState(1);
    const PAGE_SIZES = [10, 20, 30, 50, 100];
    const [pageSize, setPageSize] = useState(PAGE_SIZES[0]);
    const [initialRecords, setInitialRecords] = useState<Brand[]>([]);
    const [records, setRecords] = useState<Brand[]>([]);


    useEffect(() => {
        const from = (page - 1) * pageSize;
        const to = from + pageSize;
        setRecords([...initialRecords.slice(from, to)]);
    }, [page, pageSize, initialRecords]);


    const fetchBrands = async () => {
        try {
            const response = await axios.get('/api/brands');
            setItems(response.data);
            setInitialRecords(response.data);
        } catch (error) {
            console.error("Failed to fetch brands", error);
        }
    };

    useEffect(() => {
        fetchBrands();
    }, []);

    const handleAdd = () => {
        setIsEdit(false);
        setFormData({});
        setModal(true);
    };

    const handleSave = async () => {
        if (!formData.name) {
            // Simple validation
            return;
        }

        try {

            // Create
            await axios.post('/api/brands', formData);
            Swal.fire({ title: 'Success!', text: 'Brand has been added.', icon: 'success', customClass: 'sweet-alerts' });

            fetchBrands(); // Refresh data
            setModal(false);
        } catch (error: any) {
            if (error.response && error.response.data && error.response.data.errors) {
                const errorMessages = Object.values(error.response.data.errors).flat().join('\n');
                Swal.fire({ title: 'Error!', text: errorMessages, icon: 'error', customClass: 'sweet-alerts' });
            } else {
                Swal.fire({ title: 'Error!', text: 'Something went wrong.', icon: 'error', customClass: 'sweet-alerts' });
            }
        }
    };

    return (
        <div>
            <div className="panel">
                <div className="flex md:items-center md:flex-row flex-col mb-5 gap-5">
                    <h5 className="font-semibold text-lg dark:text-white-light">Master Brand</h5>
                    <div className="ltr:ml-auto rtl:mr-auto">
                        <button type="button" className="btn btn-primary" onClick={handleAdd}>
                            <PlusIcon className='w-4 h-4 mr-2 text-white' /> Brand
                        </button>
                    </div>
                </div>
                <div className="datatables">
                    <Table />
                </div>
            </div>

            <Transition appear show={modal} as={Fragment}>
                <Dialog as="div" open={modal} onClose={() => setModal(false)} className="relative z-50">
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-[black]/60" />
                    </Transition.Child>

                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center px-4 py-8">
                            <Transition.Child
                                as={Fragment}
                                enter="ease-out duration-300"
                                enterFrom="opacity-0 scale-95"
                                enterTo="opacity-100 scale-100"
                                leave="ease-in duration-200"
                                leaveFrom="opacity-100 scale-100"
                                leaveTo="opacity-0 scale-95"
                            >
                                <Dialog.Panel className="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                                    <div className="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                                        <h5 className="font-bold text-lg">{isEdit ? 'Edit Brand' : 'Add Brand'}</h5>
                                        <button type="button" className="text-white-dark hover:text-dark" onClick={() => setModal(false)}>
                                            <XIcon />
                                        </button>
                                    </div>
                                    <div className="p-5">
                                        <form>
                                            <div className="mb-5">
                                                <label htmlFor="name">Brand Name</label>
                                                <input
                                                    id="name"
                                                    type="text"
                                                    placeholder="Enter Brand Name"
                                                    className="form-input"
                                                    value={formData.name || ''}
                                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                                />
                                            </div>
                                            <div className="flex justify-end items-center mt-8">
                                                <button type="button" className="btn btn-outline-danger" onClick={() => setModal(false)}>
                                                    Cancel
                                                </button>
                                                <button type="button" className="btn btn-primary ltr:ml-4 rtl:mr-4" onClick={handleSave}>
                                                    {isEdit ? 'Update' : 'Save'}
                                                </button>
                                            </div>
                                        </form>
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

export default Main;
