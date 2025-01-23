package calculatie;

import static io.grpc.MethodDescriptor.generateFullMethodName;

/**
 */
@javax.annotation.Generated(
    value = "by gRPC proto compiler (version 1.68.1)",
    comments = "Source: calculatie.proto")
@io.grpc.stub.annotations.GrpcGenerated
public final class CalculationServiceGrpc {

  private CalculationServiceGrpc() {}

  public static final java.lang.String SERVICE_NAME = "calculatie.CalculationService";

  // Static method descriptors that strictly reflect the proto.
  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.CalculatePriceRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getCalculateProjectMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "CalculateProject",
      requestType = calculatie.Calculatie.CalculatePriceRequest.class,
      responseType = calculatie.Calculatie.ConfirmCalculationResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.BIDI_STREAMING)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.CalculatePriceRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getCalculateProjectMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.CalculatePriceRequest, calculatie.Calculatie.ConfirmCalculationResponse> getCalculateProjectMethod;
    if ((getCalculateProjectMethod = CalculationServiceGrpc.getCalculateProjectMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getCalculateProjectMethod = CalculationServiceGrpc.getCalculateProjectMethod) == null) {
          CalculationServiceGrpc.getCalculateProjectMethod = getCalculateProjectMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.CalculatePriceRequest, calculatie.Calculatie.ConfirmCalculationResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.BIDI_STREAMING)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "CalculateProject"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.CalculatePriceRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.ConfirmCalculationResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("CalculateProject"))
              .build();
        }
      }
    }
    return getCalculateProjectMethod;
  }

  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest,
      calculatie.Calculatie.GetCalculationResponse> getGetProjectCalculationsMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "GetProjectCalculations",
      requestType = calculatie.Calculatie.GetProjectCalculationsRequest.class,
      responseType = calculatie.Calculatie.GetCalculationResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.SERVER_STREAMING)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest,
      calculatie.Calculatie.GetCalculationResponse> getGetProjectCalculationsMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest, calculatie.Calculatie.GetCalculationResponse> getGetProjectCalculationsMethod;
    if ((getGetProjectCalculationsMethod = CalculationServiceGrpc.getGetProjectCalculationsMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getGetProjectCalculationsMethod = CalculationServiceGrpc.getGetProjectCalculationsMethod) == null) {
          CalculationServiceGrpc.getGetProjectCalculationsMethod = getGetProjectCalculationsMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.GetProjectCalculationsRequest, calculatie.Calculatie.GetCalculationResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.SERVER_STREAMING)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "GetProjectCalculations"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetProjectCalculationsRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetCalculationResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("GetProjectCalculations"))
              .build();
        }
      }
    }
    return getGetProjectCalculationsMethod;
  }

  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.GetCalculationRequest,
      calculatie.Calculatie.GetCalculationResponse> getGetCalculationMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "GetCalculation",
      requestType = calculatie.Calculatie.GetCalculationRequest.class,
      responseType = calculatie.Calculatie.GetCalculationResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.UNARY)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.GetCalculationRequest,
      calculatie.Calculatie.GetCalculationResponse> getGetCalculationMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.GetCalculationRequest, calculatie.Calculatie.GetCalculationResponse> getGetCalculationMethod;
    if ((getGetCalculationMethod = CalculationServiceGrpc.getGetCalculationMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getGetCalculationMethod = CalculationServiceGrpc.getGetCalculationMethod) == null) {
          CalculationServiceGrpc.getGetCalculationMethod = getGetCalculationMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.GetCalculationRequest, calculatie.Calculatie.GetCalculationResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.UNARY)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "GetCalculation"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetCalculationRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetCalculationResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("GetCalculation"))
              .build();
        }
      }
    }
    return getGetCalculationMethod;
  }

  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.DeleteCalculationRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getDeleteCalculationMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "DeleteCalculation",
      requestType = calculatie.Calculatie.DeleteCalculationRequest.class,
      responseType = calculatie.Calculatie.ConfirmCalculationResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.UNARY)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.DeleteCalculationRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getDeleteCalculationMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.DeleteCalculationRequest, calculatie.Calculatie.ConfirmCalculationResponse> getDeleteCalculationMethod;
    if ((getDeleteCalculationMethod = CalculationServiceGrpc.getDeleteCalculationMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getDeleteCalculationMethod = CalculationServiceGrpc.getDeleteCalculationMethod) == null) {
          CalculationServiceGrpc.getDeleteCalculationMethod = getDeleteCalculationMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.DeleteCalculationRequest, calculatie.Calculatie.ConfirmCalculationResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.UNARY)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "DeleteCalculation"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.DeleteCalculationRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.ConfirmCalculationResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("DeleteCalculation"))
              .build();
        }
      }
    }
    return getDeleteCalculationMethod;
  }

  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.UpdateCalculationRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getUpdateCalculationMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "UpdateCalculation",
      requestType = calculatie.Calculatie.UpdateCalculationRequest.class,
      responseType = calculatie.Calculatie.ConfirmCalculationResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.UNARY)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.UpdateCalculationRequest,
      calculatie.Calculatie.ConfirmCalculationResponse> getUpdateCalculationMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.UpdateCalculationRequest, calculatie.Calculatie.ConfirmCalculationResponse> getUpdateCalculationMethod;
    if ((getUpdateCalculationMethod = CalculationServiceGrpc.getUpdateCalculationMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getUpdateCalculationMethod = CalculationServiceGrpc.getUpdateCalculationMethod) == null) {
          CalculationServiceGrpc.getUpdateCalculationMethod = getUpdateCalculationMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.UpdateCalculationRequest, calculatie.Calculatie.ConfirmCalculationResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.UNARY)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "UpdateCalculation"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.UpdateCalculationRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.ConfirmCalculationResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("UpdateCalculation"))
              .build();
        }
      }
    }
    return getUpdateCalculationMethod;
  }

  /**
   * Creates a new async stub that supports all call types for the service
   */
  public static CalculationServiceStub newStub(io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<CalculationServiceStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<CalculationServiceStub>() {
        @java.lang.Override
        public CalculationServiceStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new CalculationServiceStub(channel, callOptions);
        }
      };
    return CalculationServiceStub.newStub(factory, channel);
  }

  /**
   * Creates a new blocking-style stub that supports unary and streaming output calls on the service
   */
  public static CalculationServiceBlockingStub newBlockingStub(
      io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<CalculationServiceBlockingStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<CalculationServiceBlockingStub>() {
        @java.lang.Override
        public CalculationServiceBlockingStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new CalculationServiceBlockingStub(channel, callOptions);
        }
      };
    return CalculationServiceBlockingStub.newStub(factory, channel);
  }

  /**
   * Creates a new ListenableFuture-style stub that supports unary calls on the service
   */
  public static CalculationServiceFutureStub newFutureStub(
      io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<CalculationServiceFutureStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<CalculationServiceFutureStub>() {
        @java.lang.Override
        public CalculationServiceFutureStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new CalculationServiceFutureStub(channel, callOptions);
        }
      };
    return CalculationServiceFutureStub.newStub(factory, channel);
  }

  /**
   */
  public interface AsyncService {

    /**
     * <pre>
     * Calculate the price of different articles for a project
     * </pre>
     */
    default io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceRequest> calculateProject(
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      return io.grpc.stub.ServerCalls.asyncUnimplementedStreamingCall(getCalculateProjectMethod(), responseObserver);
    }

    /**
     * <pre>
     * Get all calculations for a project
     * </pre>
     */
    default void getProjectCalculations(calculatie.Calculatie.GetProjectCalculationsRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse> responseObserver) {
      io.grpc.stub.ServerCalls.asyncUnimplementedUnaryCall(getGetProjectCalculationsMethod(), responseObserver);
    }

    /**
     * <pre>
     * Get a calculation by id -&gt; used for updating and deleting
     * </pre>
     */
    default void getCalculation(calculatie.Calculatie.GetCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse> responseObserver) {
      io.grpc.stub.ServerCalls.asyncUnimplementedUnaryCall(getGetCalculationMethod(), responseObserver);
    }

    /**
     * <pre>
     * Delete a calculation by id
     * </pre>
     */
    default void deleteCalculation(calculatie.Calculatie.DeleteCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      io.grpc.stub.ServerCalls.asyncUnimplementedUnaryCall(getDeleteCalculationMethod(), responseObserver);
    }

    /**
     * <pre>
     * Update a calculation by id
     * </pre>
     */
    default void updateCalculation(calculatie.Calculatie.UpdateCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      io.grpc.stub.ServerCalls.asyncUnimplementedUnaryCall(getUpdateCalculationMethod(), responseObserver);
    }
  }

  /**
   * Base class for the server implementation of the service CalculationService.
   */
  public static abstract class CalculationServiceImplBase
      implements io.grpc.BindableService, AsyncService {

    @java.lang.Override public final io.grpc.ServerServiceDefinition bindService() {
      return CalculationServiceGrpc.bindService(this);
    }
  }

  /**
   * A stub to allow clients to do asynchronous rpc calls to service CalculationService.
   */
  public static final class CalculationServiceStub
      extends io.grpc.stub.AbstractAsyncStub<CalculationServiceStub> {
    private CalculationServiceStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected CalculationServiceStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new CalculationServiceStub(channel, callOptions);
    }

    /**
     * <pre>
     * Calculate the price of different articles for a project
     * </pre>
     */
    public io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceRequest> calculateProject(
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      return io.grpc.stub.ClientCalls.asyncBidiStreamingCall(
          getChannel().newCall(getCalculateProjectMethod(), getCallOptions()), responseObserver);
    }

    /**
     * <pre>
     * Get all calculations for a project
     * </pre>
     */
    public void getProjectCalculations(calculatie.Calculatie.GetProjectCalculationsRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse> responseObserver) {
      io.grpc.stub.ClientCalls.asyncServerStreamingCall(
          getChannel().newCall(getGetProjectCalculationsMethod(), getCallOptions()), request, responseObserver);
    }

    /**
     * <pre>
     * Get a calculation by id -&gt; used for updating and deleting
     * </pre>
     */
    public void getCalculation(calculatie.Calculatie.GetCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse> responseObserver) {
      io.grpc.stub.ClientCalls.asyncUnaryCall(
          getChannel().newCall(getGetCalculationMethod(), getCallOptions()), request, responseObserver);
    }

    /**
     * <pre>
     * Delete a calculation by id
     * </pre>
     */
    public void deleteCalculation(calculatie.Calculatie.DeleteCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      io.grpc.stub.ClientCalls.asyncUnaryCall(
          getChannel().newCall(getDeleteCalculationMethod(), getCallOptions()), request, responseObserver);
    }

    /**
     * <pre>
     * Update a calculation by id
     * </pre>
     */
    public void updateCalculation(calculatie.Calculatie.UpdateCalculationRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse> responseObserver) {
      io.grpc.stub.ClientCalls.asyncUnaryCall(
          getChannel().newCall(getUpdateCalculationMethod(), getCallOptions()), request, responseObserver);
    }
  }

  /**
   * A stub to allow clients to do synchronous rpc calls to service CalculationService.
   */
  public static final class CalculationServiceBlockingStub
      extends io.grpc.stub.AbstractBlockingStub<CalculationServiceBlockingStub> {
    private CalculationServiceBlockingStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected CalculationServiceBlockingStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new CalculationServiceBlockingStub(channel, callOptions);
    }

    /**
     * <pre>
     * Get all calculations for a project
     * </pre>
     */
    public java.util.Iterator<calculatie.Calculatie.GetCalculationResponse> getProjectCalculations(
        calculatie.Calculatie.GetProjectCalculationsRequest request) {
      return io.grpc.stub.ClientCalls.blockingServerStreamingCall(
          getChannel(), getGetProjectCalculationsMethod(), getCallOptions(), request);
    }

    /**
     * <pre>
     * Get a calculation by id -&gt; used for updating and deleting
     * </pre>
     */
    public calculatie.Calculatie.GetCalculationResponse getCalculation(calculatie.Calculatie.GetCalculationRequest request) {
      return io.grpc.stub.ClientCalls.blockingUnaryCall(
          getChannel(), getGetCalculationMethod(), getCallOptions(), request);
    }

    /**
     * <pre>
     * Delete a calculation by id
     * </pre>
     */
    public calculatie.Calculatie.ConfirmCalculationResponse deleteCalculation(calculatie.Calculatie.DeleteCalculationRequest request) {
      return io.grpc.stub.ClientCalls.blockingUnaryCall(
          getChannel(), getDeleteCalculationMethod(), getCallOptions(), request);
    }

    /**
     * <pre>
     * Update a calculation by id
     * </pre>
     */
    public calculatie.Calculatie.ConfirmCalculationResponse updateCalculation(calculatie.Calculatie.UpdateCalculationRequest request) {
      return io.grpc.stub.ClientCalls.blockingUnaryCall(
          getChannel(), getUpdateCalculationMethod(), getCallOptions(), request);
    }
  }

  /**
   * A stub to allow clients to do ListenableFuture-style rpc calls to service CalculationService.
   */
  public static final class CalculationServiceFutureStub
      extends io.grpc.stub.AbstractFutureStub<CalculationServiceFutureStub> {
    private CalculationServiceFutureStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected CalculationServiceFutureStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new CalculationServiceFutureStub(channel, callOptions);
    }

    /**
     * <pre>
     * Get a calculation by id -&gt; used for updating and deleting
     * </pre>
     */
    public com.google.common.util.concurrent.ListenableFuture<calculatie.Calculatie.GetCalculationResponse> getCalculation(
        calculatie.Calculatie.GetCalculationRequest request) {
      return io.grpc.stub.ClientCalls.futureUnaryCall(
          getChannel().newCall(getGetCalculationMethod(), getCallOptions()), request);
    }

    /**
     * <pre>
     * Delete a calculation by id
     * </pre>
     */
    public com.google.common.util.concurrent.ListenableFuture<calculatie.Calculatie.ConfirmCalculationResponse> deleteCalculation(
        calculatie.Calculatie.DeleteCalculationRequest request) {
      return io.grpc.stub.ClientCalls.futureUnaryCall(
          getChannel().newCall(getDeleteCalculationMethod(), getCallOptions()), request);
    }

    /**
     * <pre>
     * Update a calculation by id
     * </pre>
     */
    public com.google.common.util.concurrent.ListenableFuture<calculatie.Calculatie.ConfirmCalculationResponse> updateCalculation(
        calculatie.Calculatie.UpdateCalculationRequest request) {
      return io.grpc.stub.ClientCalls.futureUnaryCall(
          getChannel().newCall(getUpdateCalculationMethod(), getCallOptions()), request);
    }
  }

  private static final int METHODID_GET_PROJECT_CALCULATIONS = 0;
  private static final int METHODID_GET_CALCULATION = 1;
  private static final int METHODID_DELETE_CALCULATION = 2;
  private static final int METHODID_UPDATE_CALCULATION = 3;
  private static final int METHODID_CALCULATE_PROJECT = 4;

  private static final class MethodHandlers<Req, Resp> implements
      io.grpc.stub.ServerCalls.UnaryMethod<Req, Resp>,
      io.grpc.stub.ServerCalls.ServerStreamingMethod<Req, Resp>,
      io.grpc.stub.ServerCalls.ClientStreamingMethod<Req, Resp>,
      io.grpc.stub.ServerCalls.BidiStreamingMethod<Req, Resp> {
    private final AsyncService serviceImpl;
    private final int methodId;

    MethodHandlers(AsyncService serviceImpl, int methodId) {
      this.serviceImpl = serviceImpl;
      this.methodId = methodId;
    }

    @java.lang.Override
    @java.lang.SuppressWarnings("unchecked")
    public void invoke(Req request, io.grpc.stub.StreamObserver<Resp> responseObserver) {
      switch (methodId) {
        case METHODID_GET_PROJECT_CALCULATIONS:
          serviceImpl.getProjectCalculations((calculatie.Calculatie.GetProjectCalculationsRequest) request,
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse>) responseObserver);
          break;
        case METHODID_GET_CALCULATION:
          serviceImpl.getCalculation((calculatie.Calculatie.GetCalculationRequest) request,
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.GetCalculationResponse>) responseObserver);
          break;
        case METHODID_DELETE_CALCULATION:
          serviceImpl.deleteCalculation((calculatie.Calculatie.DeleteCalculationRequest) request,
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse>) responseObserver);
          break;
        case METHODID_UPDATE_CALCULATION:
          serviceImpl.updateCalculation((calculatie.Calculatie.UpdateCalculationRequest) request,
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse>) responseObserver);
          break;
        default:
          throw new AssertionError();
      }
    }

    @java.lang.Override
    @java.lang.SuppressWarnings("unchecked")
    public io.grpc.stub.StreamObserver<Req> invoke(
        io.grpc.stub.StreamObserver<Resp> responseObserver) {
      switch (methodId) {
        case METHODID_CALCULATE_PROJECT:
          return (io.grpc.stub.StreamObserver<Req>) serviceImpl.calculateProject(
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.ConfirmCalculationResponse>) responseObserver);
        default:
          throw new AssertionError();
      }
    }
  }

  public static final io.grpc.ServerServiceDefinition bindService(AsyncService service) {
    return io.grpc.ServerServiceDefinition.builder(getServiceDescriptor())
        .addMethod(
          getCalculateProjectMethod(),
          io.grpc.stub.ServerCalls.asyncBidiStreamingCall(
            new MethodHandlers<
              calculatie.Calculatie.CalculatePriceRequest,
              calculatie.Calculatie.ConfirmCalculationResponse>(
                service, METHODID_CALCULATE_PROJECT)))
        .addMethod(
          getGetProjectCalculationsMethod(),
          io.grpc.stub.ServerCalls.asyncServerStreamingCall(
            new MethodHandlers<
              calculatie.Calculatie.GetProjectCalculationsRequest,
              calculatie.Calculatie.GetCalculationResponse>(
                service, METHODID_GET_PROJECT_CALCULATIONS)))
        .addMethod(
          getGetCalculationMethod(),
          io.grpc.stub.ServerCalls.asyncUnaryCall(
            new MethodHandlers<
              calculatie.Calculatie.GetCalculationRequest,
              calculatie.Calculatie.GetCalculationResponse>(
                service, METHODID_GET_CALCULATION)))
        .addMethod(
          getDeleteCalculationMethod(),
          io.grpc.stub.ServerCalls.asyncUnaryCall(
            new MethodHandlers<
              calculatie.Calculatie.DeleteCalculationRequest,
              calculatie.Calculatie.ConfirmCalculationResponse>(
                service, METHODID_DELETE_CALCULATION)))
        .addMethod(
          getUpdateCalculationMethod(),
          io.grpc.stub.ServerCalls.asyncUnaryCall(
            new MethodHandlers<
              calculatie.Calculatie.UpdateCalculationRequest,
              calculatie.Calculatie.ConfirmCalculationResponse>(
                service, METHODID_UPDATE_CALCULATION)))
        .build();
  }

  private static abstract class CalculationServiceBaseDescriptorSupplier
      implements io.grpc.protobuf.ProtoFileDescriptorSupplier, io.grpc.protobuf.ProtoServiceDescriptorSupplier {
    CalculationServiceBaseDescriptorSupplier() {}

    @java.lang.Override
    public com.google.protobuf.Descriptors.FileDescriptor getFileDescriptor() {
      return calculatie.Calculatie.getDescriptor();
    }

    @java.lang.Override
    public com.google.protobuf.Descriptors.ServiceDescriptor getServiceDescriptor() {
      return getFileDescriptor().findServiceByName("CalculationService");
    }
  }

  private static final class CalculationServiceFileDescriptorSupplier
      extends CalculationServiceBaseDescriptorSupplier {
    CalculationServiceFileDescriptorSupplier() {}
  }

  private static final class CalculationServiceMethodDescriptorSupplier
      extends CalculationServiceBaseDescriptorSupplier
      implements io.grpc.protobuf.ProtoMethodDescriptorSupplier {
    private final java.lang.String methodName;

    CalculationServiceMethodDescriptorSupplier(java.lang.String methodName) {
      this.methodName = methodName;
    }

    @java.lang.Override
    public com.google.protobuf.Descriptors.MethodDescriptor getMethodDescriptor() {
      return getServiceDescriptor().findMethodByName(methodName);
    }
  }

  private static volatile io.grpc.ServiceDescriptor serviceDescriptor;

  public static io.grpc.ServiceDescriptor getServiceDescriptor() {
    io.grpc.ServiceDescriptor result = serviceDescriptor;
    if (result == null) {
      synchronized (CalculationServiceGrpc.class) {
        result = serviceDescriptor;
        if (result == null) {
          serviceDescriptor = result = io.grpc.ServiceDescriptor.newBuilder(SERVICE_NAME)
              .setSchemaDescriptor(new CalculationServiceFileDescriptorSupplier())
              .addMethod(getCalculateProjectMethod())
              .addMethod(getGetProjectCalculationsMethod())
              .addMethod(getGetCalculationMethod())
              .addMethod(getDeleteCalculationMethod())
              .addMethod(getUpdateCalculationMethod())
              .build();
        }
      }
    }
    return result;
  }
}
