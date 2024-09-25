import { useState } from "react";
import PlusCircle from "../Icons/PlusCircle";
import FolderForm from "./FolderForm";

export default function CreateFolder({userId, parentId}) {
    const [open, setOpen] = useState(false);

    return <>
        <div 
            className="flex gap-2 w-full px-2 py-2 rounded border-2 border-gray-100 cursor-pointer"
            onClick={() => setOpen(true)}
        >

            <div className="p-4 bg-gray-50 rounded">
                <PlusCircle className="w-4 h-4" />
            </div>

            <div className="flex items-center">
                <div>New Folder</div>
            </div>
        </div>

        <FolderForm userId={userId} parentId={parentId} open={open} setOpen={setOpen} />
    </>

}