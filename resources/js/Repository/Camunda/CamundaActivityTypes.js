export const GATEWAY_TYPES = []

export default class CamundaActivityTypes {
  static MULTI_INSTANCE_BODY = 'multiInstanceBody'

  //gateways //////////////////////////////////////////////
  static GATEWAY_EXCLUSIVE = 'exclusiveGateway'
  static GATEWAY_INCLUSIVE = 'inclusiveGateway'
  static GATEWAY_PARALLEL = 'parallelGateway'
  static GATEWAY_COMPLEX = 'complexGateway'
  static GATEWAY_EVENT_BASED = 'eventBasedGateway'

  static GATEWAY_TYPES = [
    this.GATEWAY_EXCLUSIVE,
    this.GATEWAY_INCLUSIVE,
    this.GATEWAY_PARALLEL,
    this.GATEWAY_COMPLEX,
    this.GATEWAY_EVENT_BASED,
  ]

  //tasks //////////////////////////////////////////////
  static TASK = 'task'
  static TASK_SCRIPT = 'scriptTask'
  static TASK_SERVICE = 'serviceTask'
  static TASK_BUSINESS_RULE = 'businessRuleTask'
  static TASK_MANUAL_TASK = 'manualTask'
  static TASK_USER_TASK = 'userTask'
  static TASK_SEND_TASK = 'sendTask'
  static TASK_RECEIVE_TASK = 'receiveTask'

  static TASK_TYPES = [
    this.TASK,
    this.TASK_SCRIPT,
    this.TASK_SERVICE,
    this.TASK_BUSINESS_RULE,
    this.TASK_MANUAL_TASK,
    this.TASK_USER_TASK,
    this.TASK_SEND_TASK,
    this.TASK_RECEIVE_TASK,
  ]

  //other ////////////////////////////////////////////////
  static SUB_PROCESS = 'subProcess'
  static SUB_PROCESS_AD_HOC = 'adHocSubProcess'
  static CALL_ACTIVITY = 'callActivity'
  static TRANSACTION = 'transaction'

  //boundary events ////////////////////////////////////////
  static BOUNDARY_TIMER = 'boundaryTimer'
  static BOUNDARY_MESSAGE = 'boundaryMessage'
  static BOUNDARY_SIGNAL = 'boundarySignal'
  static BOUNDARY_COMPENSATION = 'compensationBoundaryCatch'
  static BOUNDARY_ERROR = 'boundaryError'
  static BOUNDARY_ESCALATION = 'boundaryEscalation'
  static BOUNDARY_CANCEL = 'cancelBoundaryCatch'
  static BOUNDARY_CONDITIONAL = 'boundaryConditional'

  //start events ////////////////////////////////////////
  static START_EVENT = 'startEvent'
  static START_EVENT_TIMER = 'startTimerEvent'
  static START_EVENT_MESSAGE = 'messageStartEvent'
  static START_EVENT_SIGNAL = 'signalStartEvent'
  static START_EVENT_ESCALATION = 'escalationStartEvent'
  static START_EVENT_COMPENSATION = 'compensationStartEvent'
  static START_EVENT_ERROR = 'errorStartEvent'
  static START_EVENT_CONDITIONAL = 'conditionalStartEvent'

  //intermediate catch events ////////////////////////////////////////
  static INTERMEDIATE_EVENT_CATCH = 'intermediateCatchEvent'
  static INTERMEDIATE_EVENT_MESSAGE = 'intermediateMessageCatch'
  static INTERMEDIATE_EVENT_TIMER = 'intermediateTimer'
  static INTERMEDIATE_EVENT_LINK = 'intermediateLinkCatch'
  static INTERMEDIATE_EVENT_SIGNAL = 'intermediateSignalCatch'
  static INTERMEDIATE_EVENT_CONDITIONAL = 'intermediateConditional'

  //intermediate throw events ////////////////////////////////
  static INTERMEDIATE_EVENT_THROW = 'intermediateThrowEvent'
  static INTERMEDIATE_EVENT_SIGNAL_THROW = 'intermediateSignalThrow'
  static INTERMEDIATE_EVENT_COMPENSATION_THROW = 'intermediateCompensationThrowEvent'
  static INTERMEDIATE_EVENT_MESSAGE_THROW = 'intermediateMessageThrowEvent'
  static INTERMEDIATE_EVENT_NONE_THROW = 'intermediateNoneThrowEvent'
  static INTERMEDIATE_EVENT_ESCALATION_THROW = 'intermediateEscalationThrowEvent'

  //end events ////////////////////////////////////////
  static END_EVENT_ERROR = 'errorEndEvent'
  static END_EVENT_CANCEL = 'cancelEndEvent'
  static END_EVENT_TERMINATE = 'terminateEndEvent'
  static END_EVENT_MESSAGE = 'messageEndEvent'
  static END_EVENT_SIGNAL = 'signalEndEvent'
  static END_EVENT_COMPENSATION = 'compensationEndEvent'
  static END_EVENT_ESCALATION = 'escalationEndEvent'
  static END_EVENT_NONE = 'noneEndEvent'
}
