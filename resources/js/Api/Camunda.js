import axios from 'axios'

const camundaRequest = (baseURL = null) => {
  return axios.create({
    baseURL,
    headers: {
      'Content-type': 'application/json',
    },
  })
}

export default camundaRequest
