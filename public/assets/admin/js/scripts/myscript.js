// $(".select2").select2({
//     placeholder: "Enter name of book",
//     //minimumInputLength: 2,
//     cache: true,
//     ajax: {
//         url: '{{ URL::route("admin.book.getAjax") }}/',
//         // delay: 250,
//         dataType: 'json',
//         data: function (params) {
//             return {
//                 q: params.term,
//                 page: params.page
//             };
//         },
//         processResults: function (data, params) {
//             return {
//                 results: data.items
//             };
//         }
//     }
// });
