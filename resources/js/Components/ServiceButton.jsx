import { useState } from "react";
import PrimaryButton from "./PrimaryButton";


export default function ServiceButton({ isSynced, sync, link, styles }) {
    const [text, setText] = useState("Copy Drive link");

    function copy() {
        navigator.clipboard.writeText(link)

        setText("Copied !");
        const timer = setTimeout(() => {
            setText("Copy Drive link");
            clearTimeout(timer);
        }, 2500);
    }

    if (isSynced) {
        return <PrimaryButton onClick={(e) => copy()} className={styles}>
            {text}
        </PrimaryButton>
    }

    return <PrimaryButton onClick={e => sync(e)} className={styles}>
        Upload in my Drive
    </PrimaryButton>
}