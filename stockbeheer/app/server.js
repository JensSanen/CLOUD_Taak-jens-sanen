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

// Definieer de types

// RackType = GraphQLObjectType stelt een rek in het magazijn voor met verschillende rijen
const RackType = new GraphQLObjectType({
    name: "Rack",
    description: "This represents a rack in the warehouse",
    fields: () => ({
        rackId: { type: GraphQLNonNull(GraphQLInt) },
        name: { type: GraphQLNonNull(GraphQLString) },
        rows: { type: GraphQLNonNull(GraphQLInt) },
        products: {
            type: new GraphQLList(ProductType),
            resolve: async (rack) => {
                try {
                    const [results] = await db.query('SELECT * FROM products WHERE locationId IN (SELECT locationId FROM locations WHERE rackId = ?)', [rack.rackId]);
                    return results;
                } catch (err) {
                    console.error('Error fetching products for rack:', err);
                    throw err;
                }
            }
        },
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
        },
        emptyLocations: {
            type: new GraphQLList(LocationType),
            resolve: async (rack) => {
                try {
                    const [results] = await db.query('SELECT locations.locationId, row FROM locations LEFT JOIN products ON locations.locationId = products.locationId WHERE products.productId IS NULL AND locations.rackId = ?', [rack.rackId]);
                    return results;
                } catch (err) {
                    console.error('Error fetching empty locations for rack:', err);
                    throw err;
                }
            }   
        }
    })
});

// LocationType = GraphQLObjectType stelt een locatie in een rek in het magazijn voor
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

// SupplierType = GraphQLObjectType stelt een leverancier van producten voor
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

// ProductType = GraphQLObjectType stelt een product in het magazijn voor
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
        locationsEmpty: {
            type: new GraphQLList(LocationType),
            description: "List of all empty locations",
            resolve: async () => {
                try {
                    const [locations] = await db.query('SELECT * FROM locations LEFT JOIN products ON locations.locationId = products.locationId WHERE products.productId IS NULL');
                    return locations;
                } catch (err) {
                    console.error('Error fetching empty locations:', err);
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
                    await db.query('DELETE FROM products WHERE supplierId = ?', [args.supplierId]);
                    return suppliers[0];
                } catch (err) {
                    console.error('Error deleting supplier:', err);
                    throw err;
                }
            }
        },
        updateSupplier: {
            type: SupplierType,
            description: "Update a supplier",
            args: {
                supplierId: { type: GraphQLNonNull(GraphQLInt) },
                name: { type: GraphQLNonNull(GraphQLString) },
                email: { type: GraphQLNonNull(GraphQLString) },
                address: { type: GraphQLNonNull(GraphQLString) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('UPDATE suppliers SET name = ?, email = ?, address = ? WHERE supplierId = ?', [args.name, args.email, args.address, args.supplierId]);
                    const [suppliers] = await db.query('SELECT * FROM suppliers WHERE supplierId = ?', [args.supplierId]);
                    return suppliers[0];
                } catch (err) {
                    console.error('Error updating supplier:', err);
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
        updateProduct: {
            type: ProductType,
            description: "Update a product",
            args: {
                productId: { type: GraphQLNonNull(GraphQLInt) },
                name: { type: GraphQLNonNull(GraphQLString) },
                description: { type: GraphQLNonNull(GraphQLString) },
                price: { type: GraphQLNonNull(GraphQLFloat) },
                quantity: { type: GraphQLNonNull(GraphQLInt) },
                locationId: { type: GraphQLNonNull(GraphQLInt) },
                supplierId: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, locationId = ?, supplierId = ? WHERE productId = ?', [args.name, args.description, args.price, args.quantity, args.locationId, args.supplierId, args.productId]);
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
        },
        addRack: {
            type: RackType,
            description: "Add a rack",
            args: {
                name: { type: GraphQLNonNull(GraphQLString) },
                rows: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    const [results] = await db.query('INSERT INTO racks (name, `rows`) VALUES (?, ?)', [args.name, args.rows]);
                    const [racks] = await db.query('SELECT * FROM racks WHERE rackId = ?', [results.insertId]);
                    
                    for (let i = 1; i <= args.rows; i++) {
                        await db.query('INSERT INTO locations (row, rackId) VALUES (?, ?)', [i, results.insertId]);
                    }

                    return racks[0];
                } catch (err) {
                    console.error('Error adding rack:', err);
                    throw err;
                }
            }
        },
        deleteRack: {
            type: RackType,
            description: "Delete a rack",
            args: {
                rackId: { type: GraphQLNonNull(GraphQLInt) }
            },
            resolve: async (parent, args) => {
                try {
                    const [racks] = await db.query('SELECT * FROM racks WHERE rackId = ?', [args.rackId]);
                    const [results] = await db.query('DELETE FROM racks WHERE rackId = ?', [args.rackId]);
                    await db.query('DELETE FROM locations WHERE rackId = ?', [args.rackId]);
                    return racks[0];
                } catch (err) {
                    console.error('Error deleting rack:', err);
                    throw err;
                }
            }
        },
        updateRack: {
            type: RackType,
            description: "Update a rack",
            args: {
                rackId: { type: GraphQLNonNull(GraphQLInt) },
                name: { type: GraphQLNonNull(GraphQLString) },
                rows: { type: GraphQLNonNull(GraphQLInt) },
            },
            resolve: async (parent, args) => {
                try {
                    currentRows = await db.query('SELECT `rows` FROM racks WHERE rackId = ?', [args.rackId]);
                    if (currentRows[0][0].rows < args.rows) {
                        for (let i = currentRows[0][0].rows + 1; i <= args.rows; i++) {
                            await db.query('INSERT INTO locations (row, rackId) VALUES (?, ?)', [i, args.rackId]);
                        }
                    } else if (currentRows[0][0].rows > args.rows) {
                        await db.query('DELETE FROM locations WHERE row > ? AND rackId = ?', [args.rows, args.rackId]);
                    }
                    
                    const [results] = await db.query('UPDATE racks SET name = ?, `rows` = ? WHERE rackId = ?', [args.name, args.rows, args.rackId]);
                    const [racks] = await db.query('SELECT * FROM racks WHERE rackId = ?', [args.rackId]);
                    return racks[0];
                } catch (err) {
                    console.error('Error updating rack:', err);
                    throw err;
                }
            }
        },
    })
});

// Maak het schema
const schema = new GraphQLSchema({
    query: RootQueryType,
    mutation: RootMutationType,
});

app.use('/graphql', graphqlHTTP({
    schema: schema,
    graphiql: true
}));

// Start de server
app.listen(30005, () => console.log('Server running on port 30005'));
