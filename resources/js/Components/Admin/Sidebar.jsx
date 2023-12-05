import React from "react";
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

export function Sidebar() {
    const [open, setOpen] = React.useState(0);

    const handleOpen = (value) => {
        setOpen(open === value ? 0 : value);
    };

    return (
        <Card className="h-[calc(100vh-6rem)] w-full max-w-[20rem] m-3 p-3 shadow-xl shadow-blue-gray-900/5">
            <List>
                <ListItem>
                    <ListItemPrefix>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6"><path strokeLinecap="round" strokeLinejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    </ListItemPrefix>
                    <Typography color="blue-gray" className="mr-auto font-normal">
                        Dashboard
                    </Typography>
                </ListItem>

                <Accordion
                    open={open === 1} 
                    icon={<ChevronDownIcon
                        strokeWidth={2.5}
                        className={`mx-auto h-4 w-4 transition-transform ${open === 1 ? "rotate-180" : ""}`}
                    />}
                >
                    <ListItem className="p-0" selected={open === 1}>
                        <AccordionHeader onClick={() => handleOpen(1)} className="border-b-0 p-3">
                            <ListItemPrefix>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6"><path strokeLinecap="round" strokeLinejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                            </ListItemPrefix>
                            <Typography color="blue-gray" className="mr-auto font-normal">
                                Reports
                            </Typography>
                        </AccordionHeader>
                    </ListItem>
                    <AccordionBody className="py-1">
                        <List className="p-0">
                            <ListItem>
                                <ListItemPrefix> </ListItemPrefix>
                                Analytics
                            </ListItem>
                            <ListItem>
                                <ListItemPrefix> </ListItemPrefix>
                                Reporting
                            </ListItem>
                            <ListItem>
                                <ListItemPrefix> </ListItemPrefix>
                                Projects
                            </ListItem>
                        </List>
                    </AccordionBody>
                </Accordion>

            </List>
        </Card>
    );
}