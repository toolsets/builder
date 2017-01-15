export default {
    getAllTables(){

        return Vue.http.get('/builder/ajax/tables');
    },

    submitNewTable(table) {

        return Vue.http.post('/builder/ajax/tables', table);
    }
}