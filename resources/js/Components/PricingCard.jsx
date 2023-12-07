import {
	Card,
	CardHeader,
	CardBody,
	CardFooter,
	Typography,
	Button,
} from "@material-tailwind/react";

function CheckIcon() {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-3 w-3" >
			<path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
		</svg>
	);
}

function UnCheckIcon() {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-3 w-3" >
			<path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
		</svg>
	);
}

export function PricingCard({
	label, 
	color,
	price, 
	buffs, 
	btnName = "Buy Now",
	onClick = ()=>{}
}) {

	return (
		<Card color={color} variant="gradient" className="w-full max-w-[20rem] p-8">
			<CardHeader
				floated={false}
				shadow={false}
				color="transparent"
				className="m-0 mb-8 rounded-none border-b border-white/10 pb-8 text-center"
			>
				<Typography
					variant="small"
					color="white"
					className="font-normal uppercase"
				>
					{ label }
				</Typography>
				<Typography
					variant="h1"
					color="white"
					className="mt-6 flex justify-center gap-1 text-7xl font-normal"
				>
					<span className="mt-2 text-4xl">$</span>{ price }{" "}
					{/* <span className="self-end text-4xl">/mo</span> */}
				</Typography>
			</CardHeader>
			<CardBody className="p-0">
				<ul className="flex flex-col gap-4">
					{buffs.map((b, i) => {
						return (
							<li className="flex items-center gap-4" key={i}>
								<span className="rounded-full border border-white/20 bg-white/20 p-1">
									{ b.value ? <CheckIcon /> : <UnCheckIcon /> }
								</span>
								<Typography className="font-normal capitalize">{ b.name }</Typography>
							</li>
						)
					})}
					
				</ul>
			</CardBody>
			<CardFooter className="mt-12 p-0">
				<Button
					size="lg"
					color="white"
					className="hover:scale-[1.02] focus:scale-[1.02] active:scale-100"
					ripple={false}
					fullWidth={true}
					onClick={onClick}
				>
					{ btnName }
				</Button>
			</CardFooter>
		</Card>
	);
}