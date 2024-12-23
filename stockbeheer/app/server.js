const express = require('express');
const { graphqlHTTP } = require('express-graphql');
const mysql = require('mysql2/promise');
const {
    GraphQLSchema,
    GraphQLObjectType,
    GraphQLString,
    GraphQLList,
    GraphQLInt,
    GraphQLFloat,
    GraphQLNonNull,
} = require('graphql');

// Maak verbinding met de database met async/await
const dbConfig = {
    host: process.env.DB_HOST || 'stockbeheer_db',
    user: process.env.DB_USER || 'stockbeheer',
    password: process.env.DB_PASS || 'stockbeheerPwd',
    database: process.env.DB_NAME || 'stockbeheerAPI'
};

// Maak een express-app
const app = express();

// Functie om verbinding te maken met de database
async function connectToDatabase() {
    try {
        const connection = await mysql.createConnection(dbConfig);
        console.log('Connected to MySQL database');
        return connection;
    } catch (err) {
        console.error('Error connecting to MySQL:', err.message);
        throw err;
    }
}

// Verbinding maken
let db;
connectToDatabase().then(connection => {
    db = connection;
}).catch(err => {
    console.error('Database connection failed', err);
    process.exit(1); // Stop de applicatie bij falen van de verbinding
});

// GraphQL types
const RackType = new GraphQLObjectType({
    name: "Rack",
    description: "This represents a rack in the warehouse",
    fields: () => ({
        rackId: { type: GraphQLNonNull(GraphQLInt) },
        name: { type: GraphQLNonNull(GraphQLString) },
        rows: { type: GraphQLNonNull(GraphQLInt) },
        locations: {
            type: new GraphQLList(LocationType),
            resolve: async (rack) => {
                try {
                    const [results] = await db.query('SELECT * FROM locations WHERE rackId = ?', [rack.rackId]);
                    return results;
                } catch (err) {
                    console.error('Error fetching locations for rack:', err);
                    throw err;
                }
            }
        }
    })
});

const LocationType = new GraphQLObjectType({
    name: "Location",
    description: "This represents a location in a rack in the warehouse",
    fields: () => ({
        locationId: { type: GraphQLNonNull(GraphQLInt) },
        row: { type: GraphQLNonNull(GraphQLInt) },
        rackId: { type: GraphQLNonNull(GraphQLInt) },
        rack: {
            type: RackType,
            resolve: async (location) => {
                try {
                    const [results] = await db.query('SELECT * FROM racks WHERE rackId = ? LIMIT 1', [location.rackId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching rack for location:', err);
                    throw err;
                }
            }
        }
    })
});

const SupplierType = new GraphQLObjectType({
    name: "Supplier",
    description: "This represents a supplier of products",
    fields: () => ({
        supplierId: { type: GraphQLNonNull(GraphQLInt) },
        name: { type: GraphQLNonNull(GraphQLString) },
        email: { type: GraphQLString },
        address: { type: GraphQLString },
        products : { 
            type: new GraphQLList(ProductType),
            resolve: async (supplier) => {
                try {
                    const [results] = await db.query('SELECT * FROM products WHERE supplierId = ?', [supplier.supplierId]);
                    return results;
                } catch (err) {
                    console.error('Error fetching products for supplier:', err);
                    throw err;
                }
            }
        }
    })
});

const ProductType = new GraphQLObjectType({
    name: "Product",
    description: "This represents a product in the warehouse",
    fields: () => ({
        productId: { type: GraphQLNonNull(GraphQLInt) },
        name: { type: GraphQLNonNull(GraphQLString) },
        description: { type: GraphQLString },
        price: { type: GraphQLNonNull(GraphQLFloat) },
        quantity: { type: GraphQLNonNull(GraphQLInt) },
        locationId: { type: GraphQLNonNull(GraphQLInt) },
        location: {
            type: LocationType,
            resolve: async (product) => {
                try {
                    const [results] = await db.query('SELECT * FROM locations WHERE locationId = ? LIMIT 1', [product.locationId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching location for product:', err);
                    throw err;
                }
            }
        },
        supplierId: { type: GraphQLNonNull(GraphQLInt) },
        supplier: {
            type: SupplierType,
            resolve: async (product) => {
                try {
                    const [results] = await db.query('SELECT * FROM suppliers WHERE supplierId = ? LIMIT 1', [product.supplierId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching supplier for product:', err);
                    throw err;
                }
            }
        }
    })
});


// RootQueryType
const RootQueryType = new GraphQLObjectType({
    name: "Query",
    description: "Root Query",
    fields: () => ({
        rack: {
            type: RackType,
            description: "A single rack by ID",
            args: {
                rackId: { type: GraphQLInt }
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('SELECT * FROM racks WHERE rackId = ? LIMIT 1', [args.rackId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching rack:', err);
                    throw err;
                }
            }
        },
        racks: {
            type: new GraphQLList(RackType),
            description: "List of all racks",
            resolve: async () => {
                try {
                    const [racks] = await db.query('SELECT * FROM racks');
                    return racks;
                } catch (err) {
                    console.error('Error fetching racks:', err);
                    throw err;
                }
            }
        },
        location: {
            type: LocationType,
            description: "A single location by ID",
            args: {
                locationId: { type: GraphQLInt }
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('SELECT * FROM locations WHERE locationId = ? LIMIT 1', [args.locationId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching location:', err);
                    throw err;
                }
            }
        },
        locations: {
            type: new GraphQLList(LocationType),
            description: "List of all locations",
            resolve: async () => {
                try {
                    const [locations] = await db.query('SELECT * FROM locations');
                    return locations;
                } catch (err) {
                    console.error('Error fetching locations:', err);
                    throw err;
                }
            }
        },
        supplier: {
            type: SupplierType,
            description: "A single supplier by ID",
            args: {
                supplierId: { type: GraphQLInt }
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('SELECT * FROM suppliers WHERE supplierId = ? LIMIT 1', [args.supplierId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching supplier:', err);
                    throw err;
                }
            }
        },
        suppliers: {
            type: new GraphQLList(SupplierType),
            description: "List of all suppliers",
            resolve: async () => {
                try {
                    const [suppliers] = await db.query('SELECT * FROM suppliers');
                    return suppliers;
                } catch (err) {
                    console.error('Error fetching suppliers:', err);
                    throw err;
                }
            }
        },
        product: {
            type: ProductType,
            description: "A single product by ID",
            args: {
                productId: { type: GraphQLInt }
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('SELECT * FROM products WHERE productId = ? LIMIT 1', [args.productId]);
                    return results[0];
                } catch (err) {
                    console.error('Error fetching product:', err);
                    throw err;
                }
            }
        },
        products: {
            type: new GraphQLList(ProductType),
            description: "List of all products",
            resolve: async () => {
                try {
                    const [products] = await db.query('SELECT * FROM products');
                    return products;
                } catch (err) {
                    console.error('Error fetching products:', err);
                    throw err;
                }
            }
        }
    })
});

// RootMutationType
const RootMutationType = new GraphQLObjectType({
    name: "Mutation",
    description: "Root Mutation",
    fields: () => ({
        addSupplier: {
            type: SupplierType,
            description: "Add a supplier",
            args: {
                name: { type: GraphQLNonNull(GraphQLString) },
                email: { type: GraphQLString },
                address: { type: GraphQLString },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('INSERT INTO suppliers (name, email, address) VALUES (?, ?, ?)', [args.name, args.email, args.address]);
                    const [suppliers] = await db.query('SELECT * FROM suppliers WHERE supplierId = ?', [results.insertId]);
                    return suppliers[0];
                } catch (err) {
                    console.error('Error adding supplier:', err);
                    throw err;
                }
            }
        },
        deleteSupplier: {
            type: SupplierType,
            description: "Delete a supplier",
            args: {
                supplierId: { type: GraphQLNonNull(GraphQLInt) }
            },
            resolve: async (parent, args) => {
                try {
                    const [suppliers] = await db.query('SELECT * FROM suppliers WHERE supplierId = ?', [args.supplierId]);
                    const [results] = await db.query('DELETE FROM suppliers WHERE supplierId = ?', [args.supplierId]);
                    return suppliers[0];
                } catch (err) {
                    console.error('Error deleting supplier:', err);
                    throw err;
                }
            }
        },
        addProduct: {
            type: ProductType,
            description: "Add a product",
            args: {
                name: { type: GraphQLNonNull(GraphQLString) },
                description: { type: GraphQLString },
                price: { type: GraphQLNonNull(GraphQLFloat) },
                quantity: { type: GraphQLNonNull(GraphQLInt) },
                locationId: { type: GraphQLNonNull(GraphQLInt) },
                supplierId: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('INSERT INTO products (name, description, price, quantity, locationId, supplierId) VALUES (?, ?, ?, ?, ?, ?)', [args.name, args.description, args.price, args.quantity, args.locationId, args.supplierId]);
                    const [products] = await db.query('SELECT * FROM products WHERE productId = ?', [results.insertId]);
                    return products[0];
                } catch (err) {
                    console.error('Error adding product:', err);
                    throw err;
                }
            }
        },
        deleteProduct: {
            type: ProductType,
            description: "Delete a product",
            args: {
                productId: { type: GraphQLNonNull(GraphQLInt) }
            },
            resolve: async (parent, args) => {
                try {
                    const [products] = await db.query('SELECT * FROM products WHERE productId = ?', [args.productId]);
                    const [results] = await db.query('DELETE FROM products WHERE productId = ?', [args.productId]);
                    return products[0];
                } catch (err) {
                    console.error('Error deleting product:', err);
                    throw err;
                }
            }
        },
        changeProductQuantity: {
            type: ProductType,
            description: "Change the quantity of a product",
            args: {
                productId: { type: GraphQLNonNull(GraphQLInt) },
                quantity: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('UPDATE products SET quantity = ? WHERE productId = ?', [args.quantity, args.productId]);
                    const [products] = await db.query('SELECT * FROM products WHERE productId = ?', [args.productId]);
                    return products[0];
                } catch (err) {
                    console.error('Error changing product quantity:', err);
                    throw err;
                }
            }
        },
        moveProduct: {
            type: ProductType,
            description: "Move a product to a different location",
            args: {
                productId: { type: GraphQLNonNull(GraphQLInt) },
                locationId: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('UPDATE products SET locationId = ? WHERE productId = ?', [args.locationId, args.productId]);
                    const [products] = await db.query('SELECT * FROM products WHERE productId = ?', [args.productId]);
                    return products[0];
                } catch (err) {
                    console.error('Error moving product:', err);
                    throw err;
                }
            }
        }
    })
});

// Maak het schema
const schema = new GraphQLSchema({
    query: RootQueryType,
    mutation: RootMutationType,
});

// Stel de GraphQL HTTP middleware in
app.use('/graphql', graphqlHTTP({
    schema: schema,
    graphiql: true
}));

// Start de server
app.listen(30005, () => console.log('Server running on port 30005'));
