import Stat from "@/Components/Admin/Stat";
import AdminLayout from "@/Layouts/AdminLayout";


export default function Index({ auth, stats }) {

    return (
        <AdminLayout user={auth.user} >
            <header className="flex gap-4 mt-6">
                { stats.map(s => <Stat {...s} key={s.label}/>) }
            </header>

        </AdminLayout>
    )
}