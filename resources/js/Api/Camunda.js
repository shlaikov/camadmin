import axios from 'axios'

const camundaRequest = (baseURL = '/instances/camunda/') => {
  return axios.create({
    baseURL,
    headers: {
      'Content-type': 'application/json',
    },
  })
}

export default camundaRequest
