import axios from 'axios'

const camundaRequest = (baseURL = '/instances/camunda/') => {
  return axios.create({
    baseURL,
    headers: {
      Accept: 'application/json',
      'Content-type': 'application/json',
    },
  })
}

export default camundaRequest
