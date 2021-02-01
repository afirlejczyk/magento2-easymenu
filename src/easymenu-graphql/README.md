# Magento 2 Easy Menu GraphQl

**CatalogGraphQl** provides type and resolver information for the GraphQl module
to generate menu information endpoints.

### Get menu tree
```
{
  menuTree {
    items {
      name
      id
      url
      type
      parent_id
      items {
        name
        id
        url
        type
        parent_id
        is_active
        value
      }
    }
  }
}
```
