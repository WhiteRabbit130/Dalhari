/*
* utils.js
*
* - This file contains utility functions that are used in multiple files
* */

import Swal from 'sweetalert2';
import moment from 'moment';

// Initialize DataTables
// initUsersTable('teacher');
function initUsersTable(type) {
    let url = '/all/users';
    let $table = $('#users-table');

    // Buttons and Icons
    let deleteButton = `
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded delete">
                <i class="fa-solid fa-trash-can mr-1"></i>
            </button>
        `;
    const usersIcon = `<div class="text-center">
            <i class="fa-solid fa-users"></i>
        </div>`;
    const teacherIcon = `<div class="text-center">
            <i class="fa-solid fa-chalkboard-teacher"></i>
        </div>`;
    const parentIcon = `<div class="text-center">
            <i class="fas fa-user-friends"></i>
        </div>`;
    const studentIcon = `<div class="text-center">
            <i class="fas fa-user-graduate"></i>
        </div>`;

    let typeIcon = usersIcon;

    switch (type) {
        case 'teacher':
            url = '/all/teachers';
            typeIcon = teacherIcon;
            $table = $('#teachers-table');
            break;
        case 'parent':
            url = '/all/parents';
            typeIcon = parentIcon;
            $table = $('#parents-table');
            break;
        case 'student':
            url = '/all/students';
            typeIcon = studentIcon;
            $table = $('#students-table');
            break;
    }

    // Get the data
    axios.get(url).then(res => {
        const {data} = res;

        // Format the data
        const tableData = data.map(n => {
            return {
                id: n.id,
                name: n.name,
                type: n.type,
                email: n.email,
                created: moment(n.created_at).format('MMMM Do YYYY, h:mm:ss a'),
            }
        });

        // Initialize DataTables
        $table.DataTable({
            data: tableData,
            columns: [
                {data: 'id'},
                {
                    data: null,
                    defaultContent: teacherIcon,
                    orderable: false
                },
                {data: 'name'},
                {data: 'type'},
                {data: 'email'},
                {data: 'created'},
                {
                    data: null, // This column does not correspond to any data field
                    defaultContent: deleteButton, // Delete button
                    orderable: false // Disable sorting for this column
                }
            ]
        });
    }).catch((e) => {
        console.log(e);
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
        });
    });

    // Delete button handler
    $table.on('click', '.delete', function (e) {
        let $row = $(this).closest('tr');
        let id = $row.find('td:first-child').text();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true, // Show cancel button
            confirmButtonColor: '#3085d6', // Blue button
            cancelButtonColor: '#d33', // Red button
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete the instrument
                axios.delete('/user/' + id).then(res => {
                    console.log(res);
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'User deleted',
                        icon: 'success',
                    });
                    // Remove the row from the table
                    $table.DataTable().row($row).remove().draw();
                }).catch((e) => {
                    console.log(e);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                });
            }
        });
    });
}
export {initUsersTable};
