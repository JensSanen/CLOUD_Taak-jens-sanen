package calculatie;

import static io.grpc.MethodDescriptor.generateFullMethodName;

/**
 * <pre>
 * Service voor het berekenen van de prijzen
 * </pre>
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
      calculatie.Calculatie.CalculatePriceResponse> getCalculateProjectMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "CalculateProject",
      requestType = calculatie.Calculatie.CalculatePriceRequest.class,
      responseType = calculatie.Calculatie.CalculatePriceResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.BIDI_STREAMING)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.CalculatePriceRequest,
      calculatie.Calculatie.CalculatePriceResponse> getCalculateProjectMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.CalculatePriceRequest, calculatie.Calculatie.CalculatePriceResponse> getCalculateProjectMethod;
    if ((getCalculateProjectMethod = CalculationServiceGrpc.getCalculateProjectMethod) == null) {
      synchronized (CalculationServiceGrpc.class) {
        if ((getCalculateProjectMethod = CalculationServiceGrpc.getCalculateProjectMethod) == null) {
          CalculationServiceGrpc.getCalculateProjectMethod = getCalculateProjectMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.CalculatePriceRequest, calculatie.Calculatie.CalculatePriceResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.BIDI_STREAMING)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "CalculateProject"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.CalculatePriceRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.CalculatePriceResponse.getDefaultInstance()))
              .setSchemaDescriptor(new CalculationServiceMethodDescriptorSupplier("CalculateProject"))
              .build();
        }
      }
    }
    return getCalculateProjectMethod;
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
   * <pre>
   * Service voor het berekenen van de prijzen
   * </pre>
   */
  public interface AsyncService {

    /**
     * <pre>
     * Bereken de prijs voor meerdere artikelen in een project
     * </pre>
     */
    default io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceRequest> calculateProject(
        io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceResponse> responseObserver) {
      return io.grpc.stub.ServerCalls.asyncUnimplementedStreamingCall(getCalculateProjectMethod(), responseObserver);
    }
  }

  /**
   * Base class for the server implementation of the service CalculationService.
   * <pre>
   * Service voor het berekenen van de prijzen
   * </pre>
   */
  public static abstract class CalculationServiceImplBase
      implements io.grpc.BindableService, AsyncService {

    @java.lang.Override public final io.grpc.ServerServiceDefinition bindService() {
      return CalculationServiceGrpc.bindService(this);
    }
  }

  /**
   * A stub to allow clients to do asynchronous rpc calls to service CalculationService.
   * <pre>
   * Service voor het berekenen van de prijzen
   * </pre>
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
     * Bereken de prijs voor meerdere artikelen in een project
     * </pre>
     */
    public io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceRequest> calculateProject(
        io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceResponse> responseObserver) {
      return io.grpc.stub.ClientCalls.asyncBidiStreamingCall(
          getChannel().newCall(getCalculateProjectMethod(), getCallOptions()), responseObserver);
    }
  }

  /**
   * A stub to allow clients to do synchronous rpc calls to service CalculationService.
   * <pre>
   * Service voor het berekenen van de prijzen
   * </pre>
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
  }

  /**
   * A stub to allow clients to do ListenableFuture-style rpc calls to service CalculationService.
   * <pre>
   * Service voor het berekenen van de prijzen
   * </pre>
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
  }

  private static final int METHODID_CALCULATE_PROJECT = 0;

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
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.CalculatePriceResponse>) responseObserver);
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
              calculatie.Calculatie.CalculatePriceResponse>(
                service, METHODID_CALCULATE_PROJECT)))
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
              .build();
        }
      }
    }
    return result;
  }
}
