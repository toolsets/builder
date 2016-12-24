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

const EmptyFormComponent = { template: '<div></div>'}
const CreateFormComponent = { template: '<h1>Create Form</h1>'}


import ReduxStore from './store'

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
