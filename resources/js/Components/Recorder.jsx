import { useEffect, useRef, useState } from "react"
import { router } from '@inertiajs/react'
import axios from "axios";

export default function Recorder() {
    const [recording, setRecording] = useState(false);
    const mediaRecorder = useRef(null);
    const [show, setShow] = useState(false);

    useEffect(() => {
        axios.post(route('video.lefted.count'))
            .then(function (response) {
                if (response.data && response.data.cnt > 0) {
                    setShow(true);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }, [])

    useEffect(() => {
        window.onbeforeunload = function(event) {
            if (! recording) {
                return;
            }

            event.preventDefault();
            event.returnValue = true;
        };

    }, [recording])

    const startRecording = async () => {
        const INTERVAL_SEC = 5000;
        let uid = "";

        try {
            const stream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });
            const chunk = [];
            const media = new MediaRecorder(stream);
            mediaRecorder.current = media;

            mediaRecorder.current.onerror = (event) => {
                console.error(`error recording stream: ${event.error.name}`);
            };


            mediaRecorder.current.ondataavailable = (e) => {
                if (e.data && e.data.size > 0) {
                    sendBlob(e.data);
                }
            }

            mediaRecorder.current.onstop = () => {
                stream.getTracks().forEach(track => track.stop());
                mediaRecorder.current = undefined;
                setRecording(false);
                finished();
            }

            mediaRecorder.current.start(INTERVAL_SEC);
            setRecording(true);

        } catch (err) {
            console.error(err);
        }

        function sendBlob(blob) {
            const body = new FormData();

            body.append('event', 'buffer');
            body.append('uuid', uid);
            body.append('data', blob);
            body.append('num', new Date().valueOf());

            axios.post(route('record'), body)
                .then((res) => {
                    uid = uid || res.data.data.uuid;
                })
                .catch((err) => {
                    console.error(err);
                });
        }

        function finished() {
            const body = new FormData();

            const param = new URLSearchParams(window.location.search);
            const folderSlug = param.get('folder');

            body.append('event', 'finished');
            body.append('uuid', uid);
            body.append('data', new Blob());
            body.append('num', new Date().valueOf());
            body.append('folder_slug', folderSlug);

            axios.post(route('record'), body)
                .then((res) => {
                    router.reload({preserveScroll: true})
                })
                .catch((err) => {
                    console.error(err);
                });
        }
    }

    const stopRecording = () => {
        if (mediaRecorder && mediaRecorder.current && mediaRecorder.current.state !== 'inactive') {
            mediaRecorder.current.stop();
        }
    };

    return <>
        {
            show && 
            <div onClick={() => recording ? stopRecording() : startRecording()} tabIndex={0} className="fixed bottom-8 right-4 sm:right-8 bg-indigo-400 p-5 rounded-full z-[10000] cursor-pointer transition-all hover:bg-opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-90">
                {recording
                    ?
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 320 512" fill="#ffffff">
                        <path d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z" />
                    </svg>
                    :
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" fill="#ffffff">
                        <path d="M0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2V384c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1V320 192 174.9l14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z" />
                    </svg>
                }
            </div>
        }
    </>
}