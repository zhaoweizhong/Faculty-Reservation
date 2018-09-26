import Mock from 'mockjs'
import '../../mock/extend'

const user = Mock.mock({
  name: '@ADMIN',
  avatar: '@AVATAR',
  address: '@CITY',
  welcome: '@WELCOME',
  timefix: '@TIMEFIX',
  position: '@POSITION'
})

Mock.mock('/login', 'post', ({body}) => {
  let result = {}
  const {sid, password} = JSON.parse(body)

  if (sid !== '11611722' || password !== '888888') {
    result.code = -1
    result.message = '用户名或密码错误'
  } else {
    result.code = 0
    //result.message = Mock.mock('@TIMEFIX') + '，欢迎回来'
    result.data = {}
    result.data.user = user
  }
  return result
})
