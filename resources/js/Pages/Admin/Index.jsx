import AdminLayout from "@/Layouts/AdminLayout";


export default function Index({ auth }) {

    return (
        <AdminLayout user={auth.user} >

        </AdminLayout>
    )
}