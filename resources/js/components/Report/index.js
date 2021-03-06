import store from '@/store'

export const API_URL = 'reports'

export const FILTERS = [
  {label: 'Год', field: 'year', type: 'multiple', options: store.state.data.years},
  {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: store.state.data.teachers, textField: 'names.abbreviation'},
  {label: 'Предмет', field: 'subject_id', type: 'multiple', options: store.state.data.subjects, textField: 'name'},
  {label: 'Тип', field: 'exists', type: 'select', options: [
    {id: 0, title: 'не созданные'},
    {id: 1, title: 'созданные'},
  ]}
]

export const REPORT_NEEDED_LESSON_COUNT = 6

export const CATEGORY = {
  homework: 'homework',
  learningAbility: 'learning_ability',
  knowledge: 'knowledge',
}


let modelDefaults = {}
Object.values(CATEGORY).forEach(value => modelDefaults[value + '_score'] = 0)

export const MODEL_DEFAULTS = modelDefaults

export const getCategoryTitle = function(category) {
  switch(category) {
    case CATEGORY.homework: return 'Выполнение домашнего задания'
    case CATEGORY.learningAbility: return 'Способность усваивать новый материал'
    case CATEGORY.knowledge: return 'Выполнение контрольных работ, текущий уровень знаний'
  }
}

export const getCategoryDescription = function(category) {
  switch(category) {
    case CATEGORY.homework: return 'Например: выполняет домашние задания регулярно, относится ответственно. Однако, достаточно встретиться нетипичным, но по сути легким заданиям, Алексей теряется и не может их решить. Довольно распространенное явление у 11-классников. Считаю, что в ближайшие 3 месяца сможем общими усилиями устранить этот недостаток.'
    case CATEGORY.learningAbility: return 'Например: как уже было указано ранее Леша хорошо усваивает новый материал и ведет себя активно на уроках. Если будет заниматься дома, то очень хорошо напишет ЕГЭ. При такой скорости усвоения материала на уроках удивляет факт неспособности справиться с нестандартными задачами. Но как уже отмечалось, должен научиться справляться с такими ситуациями.'
    case CATEGORY.knowledge: return 'Например: выполняет контрольные работы отлично. Текущий уровень знаний по математике растет очень уверенно. В конце учебного года, что касается именно математики, можно выйти на уровень, требуемый в серьезных вузах, например, МГУ, МГТУ им. Баумана, ГУ-ВШЭ и др.'
  }
}

export const getColorClass = function(score) {
  return {
    'white': !score || score === -1,
    'red': score < 4 && score > 0,
    'orange': score === 4,
    'green': score === 5,
  }
}