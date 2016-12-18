import ListViewLayout from '../../layouts/ListViewLayout.vue'

const DbTables = resolve => {
    require.ensure(['./components/DbTables.vue'], () => {
        resolve(require('./components/DbTables.vue'))
    })
}

const TableForm = resolve => {
    require.ensure(['./components/TableForm.vue'], () => {
        resolve(require('./components/TableForm.vue'))
    })
}

const EmptyFormComponent = { template: '<h1>Empty Form</h1>'}
const CreateFormComponent = { template: '<h1>Create Form</h1>'}
const ViewFormComponent = { template: '<h1>View Form</h1>'}

export default (store) => {
    return {
        path: 'database',
        components: {
            default: ListViewLayout,
        },
        children: [
            {
                path: '/',
                components: {
                    list: DbTables,
                    form: EmptyFormComponent
                }
            },

            {
                path: 'create',
                components: {
                    list: DbTables,
                    form: CreateFormComponent
                }
            },

            {
                path: ':key',
                components: {
                    list: DbTables,
                    form: TableForm
                }
            }

        ]
    };
}
