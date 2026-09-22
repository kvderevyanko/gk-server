-- Keep the previous state until the replacement is safely in place.
function saveStateFile(name, encoded)
    local temporary = name .. "-tmp"
    local backup = name .. "-bak"
    if not file.open(temporary, "w") then return false end
    local written = file.write(encoded)
    file.close()
    if not written then
        file.remove(temporary)
        return false
    end

    if file.exists(backup) and not file.remove(backup) then
        file.remove(temporary)
        return false
    end
    local hadOriginal = file.exists(name)
    if hadOriginal and not file.rename(name, backup) then
        file.remove(temporary)
        return false
    end
    if not file.rename(temporary, name) then
        if hadOriginal then file.rename(backup, name) end
        file.remove(temporary)
        return false
    end
    if hadOriginal then file.remove(backup) end
    return true
end

function readStateFile(name)
    if not file.exists(name) and file.exists(name .. "-bak") then
        file.rename(name .. "-bak", name)
    end
    if not file.open(name) then return nil end
    local encoded = file.read()
    file.close()
    return encoded
end

function sameState(a, b)
    if not a or not b then return false end
    for key, value in pairs(a) do
        if b[key] ~= value then return false end
    end
    for key in pairs(b) do
        if a[key] == nil then return false end
    end
    return true
end
