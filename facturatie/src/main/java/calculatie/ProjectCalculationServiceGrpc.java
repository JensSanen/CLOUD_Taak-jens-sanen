package calculatie;

import static io.grpc.MethodDescriptor.generateFullMethodName;

/**
 * <pre>
 * Service voor het ophalen van berekeningen voor een specifiek project
 * </pre>
 */
@javax.annotation.Generated(
    value = "by gRPC proto compiler (version 1.68.1)",
    comments = "Source: calculatie.proto")
@io.grpc.stub.annotations.GrpcGenerated
public final class ProjectCalculationServiceGrpc {

  private ProjectCalculationServiceGrpc() {}

  public static final java.lang.String SERVICE_NAME = "calculatie.ProjectCalculationService";

  // Static method descriptors that strictly reflect the proto.
  private static volatile io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest,
      calculatie.Calculatie.GetProjectCalculationsResponse> getGetProjectCalculationsMethod;

  @io.grpc.stub.annotations.RpcMethod(
      fullMethodName = SERVICE_NAME + '/' + "GetProjectCalculations",
      requestType = calculatie.Calculatie.GetProjectCalculationsRequest.class,
      responseType = calculatie.Calculatie.GetProjectCalculationsResponse.class,
      methodType = io.grpc.MethodDescriptor.MethodType.SERVER_STREAMING)
  public static io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest,
      calculatie.Calculatie.GetProjectCalculationsResponse> getGetProjectCalculationsMethod() {
    io.grpc.MethodDescriptor<calculatie.Calculatie.GetProjectCalculationsRequest, calculatie.Calculatie.GetProjectCalculationsResponse> getGetProjectCalculationsMethod;
    if ((getGetProjectCalculationsMethod = ProjectCalculationServiceGrpc.getGetProjectCalculationsMethod) == null) {
      synchronized (ProjectCalculationServiceGrpc.class) {
        if ((getGetProjectCalculationsMethod = ProjectCalculationServiceGrpc.getGetProjectCalculationsMethod) == null) {
          ProjectCalculationServiceGrpc.getGetProjectCalculationsMethod = getGetProjectCalculationsMethod =
              io.grpc.MethodDescriptor.<calculatie.Calculatie.GetProjectCalculationsRequest, calculatie.Calculatie.GetProjectCalculationsResponse>newBuilder()
              .setType(io.grpc.MethodDescriptor.MethodType.SERVER_STREAMING)
              .setFullMethodName(generateFullMethodName(SERVICE_NAME, "GetProjectCalculations"))
              .setSampledToLocalTracing(true)
              .setRequestMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetProjectCalculationsRequest.getDefaultInstance()))
              .setResponseMarshaller(io.grpc.protobuf.ProtoUtils.marshaller(
                  calculatie.Calculatie.GetProjectCalculationsResponse.getDefaultInstance()))
              .setSchemaDescriptor(new ProjectCalculationServiceMethodDescriptorSupplier("GetProjectCalculations"))
              .build();
        }
      }
    }
    return getGetProjectCalculationsMethod;
  }

  /**
   * Creates a new async stub that supports all call types for the service
   */
  public static ProjectCalculationServiceStub newStub(io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceStub>() {
        @java.lang.Override
        public ProjectCalculationServiceStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new ProjectCalculationServiceStub(channel, callOptions);
        }
      };
    return ProjectCalculationServiceStub.newStub(factory, channel);
  }

  /**
   * Creates a new blocking-style stub that supports unary and streaming output calls on the service
   */
  public static ProjectCalculationServiceBlockingStub newBlockingStub(
      io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceBlockingStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceBlockingStub>() {
        @java.lang.Override
        public ProjectCalculationServiceBlockingStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new ProjectCalculationServiceBlockingStub(channel, callOptions);
        }
      };
    return ProjectCalculationServiceBlockingStub.newStub(factory, channel);
  }

  /**
   * Creates a new ListenableFuture-style stub that supports unary calls on the service
   */
  public static ProjectCalculationServiceFutureStub newFutureStub(
      io.grpc.Channel channel) {
    io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceFutureStub> factory =
      new io.grpc.stub.AbstractStub.StubFactory<ProjectCalculationServiceFutureStub>() {
        @java.lang.Override
        public ProjectCalculationServiceFutureStub newStub(io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
          return new ProjectCalculationServiceFutureStub(channel, callOptions);
        }
      };
    return ProjectCalculationServiceFutureStub.newStub(factory, channel);
  }

  /**
   * <pre>
   * Service voor het ophalen van berekeningen voor een specifiek project
   * </pre>
   */
  public interface AsyncService {

    /**
     * <pre>
     * Haal alle berekeningen op voor een project
     * </pre>
     */
    default void getProjectCalculations(calculatie.Calculatie.GetProjectCalculationsRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetProjectCalculationsResponse> responseObserver) {
      io.grpc.stub.ServerCalls.asyncUnimplementedUnaryCall(getGetProjectCalculationsMethod(), responseObserver);
    }
  }

  /**
   * Base class for the server implementation of the service ProjectCalculationService.
   * <pre>
   * Service voor het ophalen van berekeningen voor een specifiek project
   * </pre>
   */
  public static abstract class ProjectCalculationServiceImplBase
      implements io.grpc.BindableService, AsyncService {

    @java.lang.Override public final io.grpc.ServerServiceDefinition bindService() {
      return ProjectCalculationServiceGrpc.bindService(this);
    }
  }

  /**
   * A stub to allow clients to do asynchronous rpc calls to service ProjectCalculationService.
   * <pre>
   * Service voor het ophalen van berekeningen voor een specifiek project
   * </pre>
   */
  public static final class ProjectCalculationServiceStub
      extends io.grpc.stub.AbstractAsyncStub<ProjectCalculationServiceStub> {
    private ProjectCalculationServiceStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected ProjectCalculationServiceStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new ProjectCalculationServiceStub(channel, callOptions);
    }

    /**
     * <pre>
     * Haal alle berekeningen op voor een project
     * </pre>
     */
    public void getProjectCalculations(calculatie.Calculatie.GetProjectCalculationsRequest request,
        io.grpc.stub.StreamObserver<calculatie.Calculatie.GetProjectCalculationsResponse> responseObserver) {
      io.grpc.stub.ClientCalls.asyncServerStreamingCall(
          getChannel().newCall(getGetProjectCalculationsMethod(), getCallOptions()), request, responseObserver);
    }
  }

  /**
   * A stub to allow clients to do synchronous rpc calls to service ProjectCalculationService.
   * <pre>
   * Service voor het ophalen van berekeningen voor een specifiek project
   * </pre>
   */
  public static final class ProjectCalculationServiceBlockingStub
      extends io.grpc.stub.AbstractBlockingStub<ProjectCalculationServiceBlockingStub> {
    private ProjectCalculationServiceBlockingStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected ProjectCalculationServiceBlockingStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new ProjectCalculationServiceBlockingStub(channel, callOptions);
    }

    /**
     * <pre>
     * Haal alle berekeningen op voor een project
     * </pre>
     */
    public java.util.Iterator<calculatie.Calculatie.GetProjectCalculationsResponse> getProjectCalculations(
        calculatie.Calculatie.GetProjectCalculationsRequest request) {
      return io.grpc.stub.ClientCalls.blockingServerStreamingCall(
          getChannel(), getGetProjectCalculationsMethod(), getCallOptions(), request);
    }
  }

  /**
   * A stub to allow clients to do ListenableFuture-style rpc calls to service ProjectCalculationService.
   * <pre>
   * Service voor het ophalen van berekeningen voor een specifiek project
   * </pre>
   */
  public static final class ProjectCalculationServiceFutureStub
      extends io.grpc.stub.AbstractFutureStub<ProjectCalculationServiceFutureStub> {
    private ProjectCalculationServiceFutureStub(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      super(channel, callOptions);
    }

    @java.lang.Override
    protected ProjectCalculationServiceFutureStub build(
        io.grpc.Channel channel, io.grpc.CallOptions callOptions) {
      return new ProjectCalculationServiceFutureStub(channel, callOptions);
    }
  }

  private static final int METHODID_GET_PROJECT_CALCULATIONS = 0;

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
              (io.grpc.stub.StreamObserver<calculatie.Calculatie.GetProjectCalculationsResponse>) responseObserver);
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
        default:
          throw new AssertionError();
      }
    }
  }

  public static final io.grpc.ServerServiceDefinition bindService(AsyncService service) {
    return io.grpc.ServerServiceDefinition.builder(getServiceDescriptor())
        .addMethod(
          getGetProjectCalculationsMethod(),
          io.grpc.stub.ServerCalls.asyncServerStreamingCall(
            new MethodHandlers<
              calculatie.Calculatie.GetProjectCalculationsRequest,
              calculatie.Calculatie.GetProjectCalculationsResponse>(
                service, METHODID_GET_PROJECT_CALCULATIONS)))
        .build();
  }

  private static abstract class ProjectCalculationServiceBaseDescriptorSupplier
      implements io.grpc.protobuf.ProtoFileDescriptorSupplier, io.grpc.protobuf.ProtoServiceDescriptorSupplier {
    ProjectCalculationServiceBaseDescriptorSupplier() {}

    @java.lang.Override
    public com.google.protobuf.Descriptors.FileDescriptor getFileDescriptor() {
      return calculatie.Calculatie.getDescriptor();
    }

    @java.lang.Override
    public com.google.protobuf.Descriptors.ServiceDescriptor getServiceDescriptor() {
      return getFileDescriptor().findServiceByName("ProjectCalculationService");
    }
  }

  private static final class ProjectCalculationServiceFileDescriptorSupplier
      extends ProjectCalculationServiceBaseDescriptorSupplier {
    ProjectCalculationServiceFileDescriptorSupplier() {}
  }

  private static final class ProjectCalculationServiceMethodDescriptorSupplier
      extends ProjectCalculationServiceBaseDescriptorSupplier
      implements io.grpc.protobuf.ProtoMethodDescriptorSupplier {
    private final java.lang.String methodName;

    ProjectCalculationServiceMethodDescriptorSupplier(java.lang.String methodName) {
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
      synchronized (ProjectCalculationServiceGrpc.class) {
        result = serviceDescriptor;
        if (result == null) {
          serviceDescriptor = result = io.grpc.ServiceDescriptor.newBuilder(SERVICE_NAME)
              .setSchemaDescriptor(new ProjectCalculationServiceFileDescriptorSupplier())
              .addMethod(getGetProjectCalculationsMethod())
              .build();
        }
      }
    }
    return result;
  }
}
