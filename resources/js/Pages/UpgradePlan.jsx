import { PricingCard } from '@/Components/PricingCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useMemo } from 'react';

export default function UpgradePlan ({ auth, plans }) {

    const data = useMemo(() => {

        return plans.map((plan) => {
            let p = {...plan}
            if  (p.selected == 1) {
                p.btnName = "In Use";
            }
    
            let labelledBuffs = Object.entries(p.buffs)
                .map(([k, v]) => ({ name: k, value: v }));
    
            p.labelledBuffs = labelledBuffs;
            return p;
        })

    }, [plans]);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Upgrade Plan</h2>}
        >
            <Head title="Upgrade Plan" />

            <div className="p-4 sm:pt-24 flex gap-8 flex-wrap justify-center">
                { data.map((p, i) => <PricingCard {...p} buffs={p.labelledBuffs} key={i} />) }
            </div>
        </AuthenticatedLayout>
    );
}