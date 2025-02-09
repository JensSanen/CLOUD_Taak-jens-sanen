# -*- coding: utf-8 -*-
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: calculatie.proto
# Protobuf Python Version: 5.28.1
"""Generated protocol buffer code."""
from google.protobuf import descriptor as _descriptor
from google.protobuf import descriptor_pool as _descriptor_pool
from google.protobuf import runtime_version as _runtime_version
from google.protobuf import symbol_database as _symbol_database
from google.protobuf.internal import builder as _builder
_runtime_version.ValidateProtobufRuntimeVersion(
    _runtime_version.Domain.PUBLIC,
    5,
    28,
    1,
    '',
    'calculatie.proto'
)
# @@protoc_insertion_point(imports)

_sym_db = _symbol_database.Default()




DESCRIPTOR = _descriptor_pool.Default().AddSerializedFile(b'\n\x10\x63\x61lculatie.proto\x12\ncalculatie\"\xac\x01\n\x15\x43\x61lculatePriceRequest\x12\x11\n\tprojectId\x18\x01 \x01(\x05\x12\x11\n\tarticleId\x18\x02 \x01(\x05\x12\x13\n\x0b\x64\x65scription\x18\x03 \x01(\t\x12\x17\n\x0fmeasurementType\x18\x04 \x01(\t\x12\x17\n\x0fmeasurementUnit\x18\x05 \x01(\t\x12\x10\n\x08quantity\x18\x06 \x01(\x02\x12\x14\n\x0cpricePerUnit\x18\x07 \x01(\x02\"2\n\x1dGetProjectCalculationsRequest\x12\x11\n\tprojectId\x18\x01 \x01(\x05\".\n\x15GetCalculationRequest\x12\x15\n\rcalculationId\x18\x01 \x01(\x05\"1\n\x18\x44\x65leteCalculationRequest\x12\x15\n\rcalculationId\x18\x01 \x01(\x05\"\xc6\x01\n\x18UpdateCalculationRequest\x12\x15\n\rcalculationId\x18\x01 \x01(\x05\x12\x11\n\tprojectId\x18\x02 \x01(\x05\x12\x11\n\tarticleId\x18\x03 \x01(\x05\x12\x13\n\x0b\x64\x65scription\x18\x04 \x01(\t\x12\x17\n\x0fmeasurementType\x18\x05 \x01(\t\x12\x17\n\x0fmeasurementUnit\x18\x06 \x01(\t\x12\x10\n\x08quantity\x18\x07 \x01(\x02\x12\x14\n\x0cpricePerUnit\x18\x08 \x01(\x02\"D\n\x1a\x43onfirmCalculationResponse\x12\x11\n\tarticleId\x18\x01 \x01(\x05\x12\x13\n\x0b\x64\x65scription\x18\x02 \x01(\t\"\xd8\x01\n\x16GetCalculationResponse\x12\x15\n\rcalculationId\x18\x01 \x01(\x05\x12\x11\n\tprojectId\x18\x02 \x01(\x05\x12\x11\n\tarticleId\x18\x03 \x01(\x05\x12\x13\n\x0b\x64\x65scription\x18\x04 \x01(\t\x12\x17\n\x0fmeasurementType\x18\x05 \x01(\t\x12\x17\n\x0fmeasurementUnit\x18\x06 \x01(\t\x12\x10\n\x08quantity\x18\x07 \x01(\x02\x12\x14\n\x0cpricePerUnit\x18\x08 \x01(\x02\x12\x12\n\ntotalPrice\x18\t \x01(\x02\x32\x81\x04\n\x12\x43\x61lculationService\x12\x61\n\x10\x43\x61lculateProject\x12!.calculatie.CalculatePriceRequest\x1a&.calculatie.ConfirmCalculationResponse(\x01\x30\x01\x12i\n\x16GetProjectCalculations\x12).calculatie.GetProjectCalculationsRequest\x1a\".calculatie.GetCalculationResponse0\x01\x12W\n\x0eGetCalculation\x12!.calculatie.GetCalculationRequest\x1a\".calculatie.GetCalculationResponse\x12\x61\n\x11\x44\x65leteCalculation\x12$.calculatie.DeleteCalculationRequest\x1a&.calculatie.ConfirmCalculationResponse\x12\x61\n\x11UpdateCalculation\x12$.calculatie.UpdateCalculationRequest\x1a&.calculatie.ConfirmCalculationResponseb\x06proto3')

_globals = globals()
_builder.BuildMessageAndEnumDescriptors(DESCRIPTOR, _globals)
_builder.BuildTopDescriptorsAndMessages(DESCRIPTOR, 'calculatie_pb2', _globals)
if not _descriptor._USE_C_DESCRIPTORS:
  DESCRIPTOR._loaded_options = None
  _globals['_CALCULATEPRICEREQUEST']._serialized_start=33
  _globals['_CALCULATEPRICEREQUEST']._serialized_end=205
  _globals['_GETPROJECTCALCULATIONSREQUEST']._serialized_start=207
  _globals['_GETPROJECTCALCULATIONSREQUEST']._serialized_end=257
  _globals['_GETCALCULATIONREQUEST']._serialized_start=259
  _globals['_GETCALCULATIONREQUEST']._serialized_end=305
  _globals['_DELETECALCULATIONREQUEST']._serialized_start=307
  _globals['_DELETECALCULATIONREQUEST']._serialized_end=356
  _globals['_UPDATECALCULATIONREQUEST']._serialized_start=359
  _globals['_UPDATECALCULATIONREQUEST']._serialized_end=557
  _globals['_CONFIRMCALCULATIONRESPONSE']._serialized_start=559
  _globals['_CONFIRMCALCULATIONRESPONSE']._serialized_end=627
  _globals['_GETCALCULATIONRESPONSE']._serialized_start=630
  _globals['_GETCALCULATIONRESPONSE']._serialized_end=846
  _globals['_CALCULATIONSERVICE']._serialized_start=849
  _globals['_CALCULATIONSERVICE']._serialized_end=1362
# @@protoc_insertion_point(module_scope)
