import Stat from "@/Components/Admin/Stat";
import Transactions from "@/Components/Admin/Transactions";
import AdminLayout from "@/Layouts/AdminLayout";


export default function Index({ auth, stats, transactions }) {
    return (
        <AdminLayout user={auth.user} >
            <header className="flex gap-4 mt-6">
                { stats.map(s => <Stat {...s} key={s.label}/>) }
            </header>

            <Transactions data={transactions} />
        </AdminLayout>
    )
}