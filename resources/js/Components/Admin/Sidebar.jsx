import React, { Fragment } from "react";
import {
    Card,
    Typography,
    List,
    ListItem,
    ListItemPrefix,
    ListItemSuffix,
    Chip,
    Accordion,
    AccordionHeader,
    AccordionBody,
} from "@material-tailwind/react";
import ChevronDownIcon from "../Icons/ChevronDownIcon";
import { Link } from "@inertiajs/react";

export function Sidebar() {
    const [open, setOpen] = React.useState(0);

    const handleOpen = (value) => {
        setOpen(open === value ? 0 : value);
    };

    const config = [
        {
            name: "Dashboard", 
            icon: <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6"><path strokeLinecap="round" strokeLinejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
        },
        {
            name: "Reports",
            icon: <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6"><path strokeLinecap="round" strokeLinejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>,
            children: [
                {name: "Analytics", icon: null},
                {name: "Reporting", icon: null},
                {name: "Projects", icon: null}
            ]
        },
        {
            name: "Plans", 
            icon: <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
            </svg>          
        }
    ]

    const singleItem = (item) => (
        <Link href={item.url ?? "#"}>
            <ListItem>
                <ListItemPrefix>{item.icon ? item.icon : ' '}</ListItemPrefix>
                <Typography color="blue-gray" className="mr-auto font-normal">{item.name}</Typography>
            </ListItem> 
        </Link>
    )

    const multiItem = (item, i) => (
        <Accordion
            open={open === i} 
            icon={<ChevronDownIcon
                strokeWidth={2.5}
                className={`mx-auto h-4 w-4 transition-transform ${open === i ? "rotate-180" : ""}`}
            />}
        >
            <ListItem className="p-0" selected={open === i}>
                <AccordionHeader onClick={() => handleOpen(i)} className="border-b-0 p-3">
                    <ListItemPrefix>{ item.icon ? item.icon : ' ' }</ListItemPrefix>
                    <Typography color="blue-gray" className="mr-auto font-normal">{ item.name }</Typography>
                </AccordionHeader>
            </ListItem>
            <AccordionBody className="py-1">
                <List className="p-0">
                    { item.children.map((child, c) => <Fragment key={'child'+c}>
                        {singleItem(child)}
                    </Fragment>) }
                </List>
            </AccordionBody>
        </Accordion>
    )

    return (
        <Card className="h-[calc(100vh-6rem)] w-full max-w-[20rem] m-3 p-3 shadow-xl shadow-blue-gray-900/5">
            <List>
                {config.map((item, i) => <Fragment key={i}>
                    { item.children ? multiItem(item, i): singleItem(item) }
                </Fragment>)}
            </List>
        </Card>
    );
}