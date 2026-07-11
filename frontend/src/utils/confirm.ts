import Swal from "sweetalert2";

export async function confirmDelete(name: string | number) {
    return Swal.fire({
        title: "Delete Item?",
        text: `Are you sure you want to delete ${name}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
    });
}
