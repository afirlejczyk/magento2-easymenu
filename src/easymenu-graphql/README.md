# Magento 2 Easy Menu GraphQl

**CatalogGraphQl** provides type and resolver information for the GraphQl module
to generate menu information endpoints.

### Get menu tree for given store
```
{
  menuTree (store_id: 1) {
    items {
      name
      id
      parent_id
      items {
        name
        id
        parent_id
        is_active
        value
      }
    }
  }
}
```
