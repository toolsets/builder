import UiSplitViewController from '../../layouts/UiSplitViewController.vue'


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


const CreateFormComponent = resolve => {
    require.ensure(['./components/CreateForm.vue'], () => {
        resolve(require('./components/CreateForm.vue'))
    })
}


const UpdateFormComponent = resolve => {
    require.ensure(['./components/UpdateForm.vue'], () => {
        resolve(require('./components/UpdateForm.vue'))
    })
}


const TableUpdateForm = resolve => {
    require.ensure(['./components/UpdateForm.vue'], () => {
        resolve(require('./components/UpdateForm.vue'))
    })
}

const EmptyFormComponent = { template: '<div></div>'}


import ReduxStore from './store/store'

export default (store) => {



    store.registerModule('database', ReduxStore);

    return {
        path: 'database',
        components: {
            default: UiSplitViewController,
        },
        children: [
            {
                path: '/',
                name: 'db-tables',
                components: {
                    list: DbTables,
                    form: EmptyFormComponent
                }
            },

            {
                path: 'create',
                name: 'db-table-create',
                components: {
                    list: DbTables,
                    form: CreateFormComponent
                }
            },

            {
                path: ':key',
                name: 'db-table-view',
                components: {
                    list: DbTables,
                    form: TableForm
                },

                children : [
                    {
                        path: 'update',
                        name: 'db-table-update',
                        components: {
                            list: DbTables,
                            form: TableForm,
                            update_form: UpdateFormComponent
                        }
                    }
                ]
            }

        ]
    };
}
