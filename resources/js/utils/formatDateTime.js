import moment from 'moment'

export const formatDateTime = (data) => {
  return moment(data).format('MMMM Do YYYY, h:mm a')
}
