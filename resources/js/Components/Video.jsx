import { Link } from "@inertiajs/react";


export default function Video({data}) {
    return <Link href={`videos/${data.hash}`} className="relative w-64 mx-auto sm:w-[unset] sm:mx-[unset] h-48 bg-gray-100 rounded cursor-pointer shadow">
        <svg className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-1/3 fill-stone-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <path d="M0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2V384c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1V320 192 174.9l14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z"/>
        </svg>
        <div className="absolute bottom-0 p-2 text-gray-500 text-medium">{ data.title }</div>
    </Link>
}