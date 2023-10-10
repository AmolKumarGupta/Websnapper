import { Link, Head } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import NavLink from '@/Components/NavLink';
import LeftShade from '@/Components/Design/LeftShade';
import RightShade from '@/Components/Design/RightShade';
import Footer from '@/Components/Design/Footer';

export default function Welcome({ auth }) {
    return (
        <>
            <Head title="Welcome" />
            <div className="relative">
                <LeftShade />
                <RightShade />

                <nav className="w-[max(60%,_320px)] m-auto border-b-[1px] border-gray-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex">
                                <div className="shrink-0 flex items-center">
                                    <Link href="/">
                                        <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
                                    </Link>
                                </div>

                                <div className="hidden sm:block space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink href={route('home')} >
                                        Web Snapper
                                    </NavLink>
                                </div>
                            </div>

                            <div className="flex items-center ml-6">
                                <div className="ml-3 relative">
                                    {auth.user ? (
                                        <Link
                                            href={route('dashboard')}
                                            className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                        >
                                            Dashboard
                                        </Link>
                                    ) : (
                                        <>
                                            <Link
                                                href={route('login')}
                                                className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                            >
                                                Log in
                                            </Link>

                                            <Link
                                                href={route('register')}
                                                className="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                            >
                                                Register
                                            </Link>
                                        </>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

            </div>

            <div className="relative pt-32 pb-10 md:pt-40 md:pb-16">
                <div className="max-w-3xl mx-auto text-center pb-12 md:pb-16">
                    <h1 className="text-4xl lg:text-6xl mb-4" >Videos can attract a different audience</h1>
                    <p className="text-xl text-gray-500 mb-8">Easily record and share with your friends, Super charge your productivity with Web Snapper.</p>
                    <div className="max-w-xs mx-auto sm:max-w-none sm:flex sm:justify-center">
                        <div >
                            <a className="px-4 py-2 rounded text-white bg-stone-900 hover:bg-stone-800 w-full mb-4 sm:w-auto sm:mb-0" href="#">Start free trial</a>
                        </div>
                        <div className='hidden'>
                            <a className="btn text-white bg-gray-700 hover:bg-gray-800 w-full sm:w-auto sm:ml-4" href="#">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>

            <Footer />
        </>
    );
}
