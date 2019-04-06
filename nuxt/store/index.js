export const state = () => ({
  tables: []
})

export const mutations = {
  setTable(state, tables) {
    state.tables = tables
  },
  appendTable(state, table) {
    state.tables.push(table)
  },
  updateTable(state, table) {
    const fieldIndex = state.tables.findIndex(x => x.id === table.id)
    state.tables[fieldIndex] = table
  }
}
