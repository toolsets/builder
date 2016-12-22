export default {
    getAllTables(){

        return Vue.http.get('/builder/ajax/tables');
    }
}