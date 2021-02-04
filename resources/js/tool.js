import Add from './components/icons/Add'
import AddResourceButton from './components/AddResourceButton'
import ArrowDown from './components/icons/ArrawDown'
import CollapseResources from './components/CollapseResources'
import ResourceTable from "./components/ResourceTable";
import ResourceTableRow from "./components/ResourceTableRow";

import VueCollapse from 'vue2-collapse/src'

Nova.booting((Vue, router, store) => {
  Vue.use(VueCollapse)
  Vue.component('icon-add-button', Add)
  Vue.component('add-resource-button', AddResourceButton)
  Vue.component('icon-arrow-down', ArrowDown)
  Vue.component('resource-table', ResourceTable)
  Vue.component('resource-table-row', ResourceTableRow)
  Vue.component('collapse-resources', CollapseResources)

  router.addRoutes([
    {
      name: 'collapse-relationships',
      path: '/collapse-relationships',
      component: require('./components/Tool'),
    },
  ])
})
