local function uriToFilename(uri)
    if uri == "/" then return "index.html" end
    if string.sub(uri, 1, 1) ~= "/" then return nil end
    local filename = string.sub(uri, 2)
    if filename == "" or string.find(filename, "..", 1, true) then return nil end
    if not string.match(filename, "^[%w%._%-/]+$") then return nil end
    return filename
end

local function parseUri(uri)
    if not uri then return {} end
    local question = string.find(uri, "?", 1, true)
    if not question then return {file = uriToFilename(uri), query = ""} end
    return {
        file = uriToFilename(string.sub(uri, 1, question - 1)),
        query = string.sub(uri, question + 1),
    }
end

return function(uri)
    return parseUri(uri)
end
