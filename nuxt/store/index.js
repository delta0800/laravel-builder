export const state = () => ({
  tables: [],
  projects: []
})

export const mutations = {
  setProject(state, project) {
    state.projects = project
  },
  appendProject(state, project) {
    state.projects.push(project)
  },

  setTable(state, table) {
    state.tables = table
  },
  appendTable(state, table) {
    state.tables.push(table)
  },
  updateTable(state, table) {
    const fieldIndex = state.tables.findIndex(x => x.id === table.id)
    state.tables[fieldIndex] = table
  }
}
