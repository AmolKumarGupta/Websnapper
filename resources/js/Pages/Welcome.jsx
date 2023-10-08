import { Link, Head } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import NavLink from '@/Components/NavLink';
import LeftShade from '@/Components/Design/LeftShade';
import RightShade from '@/Components/Design/RightShade';

export default function Welcome({ auth }) {
    return (
        <>
            <Head title="Welcome" />
            <div className="relative">
                <LeftShade/>
                <RightShade/>

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
            <div className="container mx-auto"></div>
        </>
    );
}
