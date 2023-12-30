import { PricingCard } from '@/Components/PricingCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useMemo } from 'react';

export default function UpgradePlan ({ auth, plans }) {

    const data = useMemo(() => {

        return plans.map((plan) => {
            let p = {...plan}

            if (p.hide == 1) {
                return null;
            }

            if (p.selected == 1) {
                p.btnName = "In Use";
            }
            
            let labelledBuffs = Object.entries(p.buffs)
            .map(([k, v]) => {
                if (k == 'videos') {
                    k = `${v} videos`
                }
                return { name: k, value: v }
            });
            
            p.labelledBuffs = labelledBuffs;
            p.link = (p.selected == 1) ? "" : route('upgrade', {plan: p.id});
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
                { 
                    data.filter((p) => p)
                    .map((p, i) => <PricingCard {...p} buffs={p.labelledBuffs} key={i} />) 
                }
            </div>
        </AuthenticatedLayout>
    );
}