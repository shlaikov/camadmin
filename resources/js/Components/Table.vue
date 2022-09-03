<template>
  <table-lite
    :is-slot-mode="true"
    :is-loading="table.isLoading"
    :columns="table.columns"
    :rows="table.rows"
    :total="table.totalRecordCount"
    :page="table.currentPage"
    :page-size="table.pageSize"
    :sortable="table.sortable"
    :messages="table.messages"
    @row-clicked="table.rowClicked"
    @is-finished="table.isLoading = false"
  >
  </table-lite>
</template>

<script>
import { defineComponent, reactive } from 'vue'
import { default as TableLite } from 'vue3-table-lite'

export default defineComponent({
  name: 'TableView',
  components: { TableLite },
  props: {
    rows: {
      type: Object,
      required: true,
    },
    columns: {
      type: Array,
      required: true,
    },
    rowClicked: {
      type: Function,
      required: false,
      default: () => {},
    },
  },
  setup(props) {
    const table = reactive({
      isLoading: false,
      columns: props.columns,
      rows: props.rows.data,
      totalRecordCount: props.rows.total,
      currentPage: props.rows.current_page,
      pageSize: props.rows.per_page,
      links: props.rows.links,
      rowClicked: props.rowClicked,
      messages: {
        pagingInfo: 'Showing {0}-{1} of {2}',
        pageSizeChangeLabel: 'Row count:',
        gotoPageLabel: 'Go to page:',
        noDataAvailable: 'No data',
      },
      sortable: {
        order: 'id',
        sort: 'asc',
      },
    })

    return {
      table,
    }
  },
})
</script>

<style scoped>
::v-deep(.vtl-asc) {
  background-repeat: no-repeat;
  background-position: bottom;
}
::v-deep(.vtl-table) {
  display: table;
}
::v-deep(.vtl-table .vtl-thead .vtl-thead-th) {
  width: 100%;
  color: #fff;
  background-color: #7f9cf5;
  border-color: #7f9cf5;
}
::v-deep(.vtl-table td),
::v-deep(.vtl-table tr) {
  border: none;
}
::v-deep(.vtl-paging-pagination-page-link) {
  border: none;
}
::v-deep(.vtl-paging-page-dropdown),
::v-deep(.vtl-paging-count-dropdown) {
  margin: 0;
  padding: 2px 32px;
  border: 0;
  box-shadow: none;
  outline: none;
}
::v-deep(.vtl-paging-page-dropdown:focus),
::v-deep(.vtl-paging-count-dropdown:focus) {
  outline: none;
  box-shadow: none;
}
</style>
