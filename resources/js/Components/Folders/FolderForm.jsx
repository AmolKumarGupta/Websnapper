import { useForm } from '@inertiajs/react';
import { Dialog, Transition } from '@headlessui/react';
import { Fragment } from 'react';

export default function FolderForm({ open, setOpen, userId, parentId }) {
    const { data, setData, post, processing, errors } = useForm({
        userId,
        parentId,
        folder: '',
    });

    function process(e) {
        post(route('folders.store'), { 
            onSuccess: () => setOpen(false)
        })
    }

    return <Transition appear show={open} as={Fragment}>
        <Dialog as="div" className="relative z-10" onClose={() => setOpen(false)}>
            <Transition.Child
                as={Fragment}
                enter="ease-out duration-300"
                enterFrom="opacity-0"
                enterTo="opacity-100"
                leave="ease-in duration-200"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
            >
                <div className="fixed inset-0 bg-black/25" />
            </Transition.Child>

            <div className="fixed inset-0 overflow-y-auto">
                <div className="flex min-h-full items-center justify-center p-4 text-center">
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0 scale-95"
                        enterTo="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100 scale-100"
                        leaveTo="opacity-0 scale-95"
                    >
                        <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <Dialog.Title
                                as="h3"
                                className="text-lg font-medium leading-6 text-gray-900"
                            >
                                Create New Folder
                            </Dialog.Title>

                            <div className="mt-2">
                                <input value={data.folder} onChange={e => setData('folder', e.target.value)} type="text" className="w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md focus:shadow-sm block" placeholder='My Folder' />
                                { errors.folder && <div className='text-red-500'>{errors.folder}</div> }
                            </div>

                            <div className="mt-4 flex justify-end gap-2">
                                <button
                                    type="button"
                                    className="inline-flex justify-center rounded-md border border-transparent bg-green-300 px-4 py-2 text-sm font-medium text-gray-50 hover:bg-green-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                                    onClick={process} 
                                    disabled={processing} 
                                >
                                    Create
                                </button>

                                <button
                                    type="button"
                                    className="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                    onClick={() => setOpen(false)}
                                >
                                    Close
                                </button>
                            </div>
                        </Dialog.Panel>
                    </Transition.Child>
                </div>
            </div>
        </Dialog>
    </Transition>
}