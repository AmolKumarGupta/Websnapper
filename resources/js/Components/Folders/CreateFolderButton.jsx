import { useState } from "react";
import PlusCircle from "../Icons/PlusCircle";
import FolderForm from "./FolderForm";

export default function CreateFolderButton({ userId, parentId }) {
    const [open, setOpen] = useState(false);

    return <>
        <button
            type="button"
            className="hidden md:block rounded-2xl bg-green-400 bg-opacity-80 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-70 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
            onClick={() => setOpen(s => !s)}
        >
            <PlusCircle className="inline w-5 h-5"/> Folder
        </button>

        <FolderForm userId={userId} parentId={parentId} open={open} setOpen={setOpen} />
    </>
}