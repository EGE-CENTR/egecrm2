export const API_URL = 'tests'
export const CLIENT_TESTS_API_URL = 'client-tests'
export const CLIENT_TEST_ANSWERS_API_URL = 'client-test-answers'

export const SETTINGS_KEY = 'test-intro-text'

export const PROBLEM_DEFAULTS = {
  text: '',
  answers: [{text: ''}]
}

export const MODEL_DEFAULTS = {
  problems: [PROBLEM_DEFAULTS]
}

export const ANSWER_DEFAULTS = {}

export const TestAdminClientList = require('./Admin/ClientList')
export const TestIntroTextDialog = require('./Admin/IntroTextDialog')
export const TestDialog = require('./Admin/Dialog')
export const TestList = require('./Admin/List')