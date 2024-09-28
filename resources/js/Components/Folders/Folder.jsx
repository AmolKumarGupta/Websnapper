import FolderIcon from "../Icons/Folder";

export default function Folder({ model }) {

    return <>
        <div 
            className="flex gap-2 w-full px-2 py-2 rounded border-2 border-gray-100 cursor-pointer"
        >

            <div className="p-4 bg-gray-50 rounded">
                <FolderIcon className="w-4 h-4" />
            </div>

            <div className="flex flex-col">
                <div className="text-md">{model.name}</div>
                <div className="text-sm text-gray-700">{model.date}</div>
            </div>
        </div>
    </>

}