/*
* admin.index.js
* - This file is the entry point for the admin dashboard
* */

import Swal from 'sweetalert2';
import moment from 'moment';

// Initialize DataTables
initInstrumentsTable();
initUsersTable();

// Initialize DataTables
function initInstrumentsTable() {
    let $table = $('#instruments-table');
    // get all instruments
    axios.get('/instruments').then(res => {
        let instruments = res.data.data;
        let deleteButton = `
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded delete">
                <i class="fa-solid fa-trash-can mr-1"></i>
            </button>
        `;
        // Format the data
        const $data = instruments.map(n => {
            return {
                id: n.id,
                name: n.name,
                hourly_rate: '$' + n.hourly_rate.toFixed(2), // '$' + n.hourly_rate.toFixed(2) + '/hr
                created: moment(n.created_at).format('MMMM Do YYYY, h:mm:ss a'),
                updated: moment(n.updated_at).format('MMMM Do YYYY, h:mm:ss a'),
            }
        });
        // Initialize DataTables
        $table.DataTable({
            data: $data,
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'hourly_rate'},
                {data: 'created'},
                {data: 'updated'},
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

    // Catch delete button click
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
                axios.delete('/instrument/' + id).then(res => {
                    console.log(res);
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Instrument deleted',
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

function initUsersTable() {
    let $table = $('#users-table');

    // get all users
    axios.get('/all/users').then(res => {
        const {data} = res;
        let deleteButton = `
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded delete">
                <i class="fa-solid fa-trash-can mr-1"></i>
            </button>
        `;
        // Format the data
        const tableData = data.map(n => {
            return {
                id: n.id,
                name: n.name,
                email: n.email,
                created: moment(n.created_at).format('MMMM Do YYYY, h:mm:ss a'),
            }
        });
        // Initialize DataTables
        $table.DataTable({
            data: tableData,
            columns: [
                {data: 'id'},
                {data: 'name'},
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

    // todo - add delete user functionality
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


