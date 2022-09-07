const { defineConfig } = require("cypress");

module.exports = defineConfig({
  projectId: 'tc7an8',
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    baseUrl: 'http://127.0.0.1:8000/'
  },
});
