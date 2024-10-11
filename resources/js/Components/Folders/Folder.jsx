import { Link } from "@inertiajs/react";
import FolderIcon from "../Icons/Folder";
import { slugify } from "@/helpers";
import FolderMenu from "./FolderMenu";

export default function Folder({ model }) {

    return <>
        <div className="flex gap-2 w-full px-2 py-2 rounded border-2 border-gray-100">

            <div className="p-4 bg-gray-50 rounded">
                <FolderIcon className="w-4 h-4" />
            </div>

            <Link className="flex flex-col flex-1 cursor-pointer" href={`?folder=${slugify(model.name)}~${model.hash}`}>
                <div className="text-md">{model.name}</div>
                <div className="text-sm text-gray-700">{model.date}</div>
            </Link>

            <div>
                <FolderMenu folder={model} />
            </div>
        </div>
    </>

}